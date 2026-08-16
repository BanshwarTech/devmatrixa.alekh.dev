<script setup>
import { ref, computed } from 'vue';
import StatTile from './shared/StatTile.vue';

const url = ref('');
const focused = ref(false);
const data = ref(null);
const loading = ref(false);
const error = ref('');
const checking = ref(false);
const progress = ref(0);
const copiedAll = ref(false);
const copiedIdx = ref(null);
const filter = ref('all');

const okCount = computed(() => (data.value ?? []).filter((d) => d.status !== null && d.status < 400).length);
const errCount = computed(() => (data.value ?? []).filter((d) => (d.status !== null && d.status >= 400) || d.status === 0).length);
const visible = computed(() => {
  if (!data.value) return [];
  return data.value.filter((d) => {
    if (filter.value === 'ok') return d.status !== null && d.status < 400;
    if (filter.value === 'errors') return (d.status !== null && d.status >= 400) || d.status === 0;
    return true;
  });
});

function statusColor(s) {
  if (s === null) return '#a3a3a3';
  if (s >= 200 && s < 300) return '#65a30d';
  if (s >= 300 && s < 400) return '#f59e0b';
  return '#f87171';
}

async function copyOne(u, idx) {
  try {
    await navigator.clipboard.writeText(u);
    copiedIdx.value = idx;
    setTimeout(() => { if (copiedIdx.value === idx) copiedIdx.value = null; }, 1500);
  } catch { /* ignore */ }
}

async function copyAll(list) {
  const src = list ?? data.value;
  if (!src || src.length === 0) return;
  try {
    await navigator.clipboard.writeText(src.map((d) => d.url).join('\n'));
    copiedAll.value = true;
    setTimeout(() => { copiedAll.value = false; }, 1800);
  } catch { /* ignore */ }
}

async function crawl() {
  const u = url.value.trim();
  if (!u) { error.value = 'Please enter a URL.'; return; }
  if (!/^https?:\/\/.+/.test(u)) { error.value = 'URL must start with http:// or https://'; return; }

  error.value = '';
  data.value = null;
  loading.value = true;

  try {
    const res = await fetch('/api/link-checker', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ url: u }),
    });
    const j = await res.json();
    if (!res.ok || j.error) {
      error.value = j.error || 'Failed';
    } else {
      data.value = j.urls.map((linkUrl) => ({ url: linkUrl, status: null }));
      checkAll(j.urls);
    }
  } catch {
    error.value = 'Network error.';
  }

  loading.value = false;
}

async function checkAll(urls) {
  checking.value = true;
  progress.value = 0;
  const results = urls.map((u) => ({ url: u, status: null }));
  data.value = [...results];

  const concurrency = 6;
  let done = 0;
  let i = 0;
  const worker = async () => {
    while (i < urls.length) {
      const myIdx = i++;
      const u = urls[myIdx];
      try {
        const res = await fetch('/api/link-status', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ url: u }),
        });
        const j = await res.json();
        results[myIdx].status = j.status ?? 0;
      } catch {
        results[myIdx].status = 0;
      }
      done++;
      progress.value = done;
      if (done % 3 === 0 || done === urls.length) data.value = [...results];
    }
  };

  await Promise.all(Array.from({ length: concurrency }, worker));
  data.value = [...results];
  checking.value = false;
}
</script>

<template>
  <div class="url-form relative glass rounded-[28px] p-6 sm:p-9 md:p-11 mb-10 overflow-hidden">
    <div class="absolute top-0 left-8 right-8 h-[2px] rounded-b-full" style="background:linear-gradient(90deg, transparent, #0694a2, #a3e635, #16bdca, transparent)"></div>
    <div class="url-form-mesh"></div>

    <div class="relative z-10">
      <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-7">
        <div class="min-w-0">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest mb-3" style="background:rgba(6,148,162,0.08);border:1px solid rgba(6,148,162,0.20);color:#0694a2">
            <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:#0694a2"></span>
            Devmatrixa Tool
          </div>
          <h2 class="font-sans text-2xl sm:text-3xl font-700 tracking-tight leading-tight">Enter a URL to crawl.</h2>
        </div>
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold shrink-0 self-start" style="background:rgba(163,230,53,0.10);border:1px solid rgba(163,230,53,0.28);color:#65a30d">
          <i class="fa-solid fa-bolt text-xs"></i>
          Fast &amp; free
        </div>
      </div>

      <div :class="['url-input-wrap', focused ? 'focused' : '']" class="flex gap-2 sm:gap-3 flex-col sm:flex-row p-1.5 rounded-2xl">
        <div class="relative flex-1">
          <i class="fa-solid fa-globe absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none text-sm" :style="{ color: focused ? '#0694a2' : 'var(--c-muted)' }"></i>
          <input
            v-model="url"
            type="text"
            @focus="focused = true"
            @blur="focused = false"
            @keydown.enter="!loading && crawl()"
            placeholder="Paste your page URL here"
            spellcheck="false"
            autocapitalize="none"
            autocorrect="off"
            class="w-full pl-11 pr-5 py-3.5 rounded-xl text-sm bg-transparent outline-none"
            style="color:var(--c-text)"
          >
        </div>
        <button @click="crawl" :disabled="loading" class="btn-primary px-7 py-3.5 rounded-xl text-sm font-700 inline-flex items-center justify-center gap-2 whitespace-nowrap disabled:opacity-70 disabled:cursor-not-allowed">
          <template v-if="loading">
            <span class="w-3.5 h-3.5 rounded-full border-2 border-current border-r-transparent animate-spin" style="color:#061c21"></span>
            Analyzing
          </template>
          <template v-else>
            <i class="fa-solid fa-bug text-xs"></i>
            Crawl
          </template>
        </button>
      </div>

      <div v-if="error" class="mt-5 rounded-2xl px-4 py-3 text-sm font-semibold flex items-start gap-3" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);color:#ef4444">
        <i class="fa-solid fa-triangle-exclamation mt-0.5 shrink-0"></i>
        <span class="leading-snug">{{ error }}</span>
      </div>
    </div>
  </div>

  <div v-if="data" class="space-y-4">
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
      <StatTile :value="data.length" label="Found" color="#0694a2" clickable :active="filter === 'all'" @click="filter = 'all'" />
      <StatTile :value="okCount" label="OK" color="#65a30d" clickable :active="filter === 'ok'" @click="filter = filter === 'ok' ? 'all' : 'ok'" />
      <StatTile :value="errCount" label="Errors" color="#f87171" clickable :active="filter === 'errors'" @click="filter = filter === 'errors' ? 'all' : 'errors'" />
      <StatTile :value="`${progress}/${data.length}`" label="Checked" color="#0694a2" />
    </div>

    <div v-if="checking" class="h-2 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.06)">
      <div class="h-full transition-all duration-300" :style="{ width: `${(progress / data.length) * 100}%`, background: 'linear-gradient(90deg,#0694a2,#a3e635)' }"></div>
    </div>

    <div class="glass rounded-3xl p-4 space-y-2 max-h-[600px] overflow-y-auto">
      <div class="flex items-center justify-between gap-3 px-3 py-2.5 mb-2 sticky top-0 z-10 rounded-xl backdrop-blur-md" style="background:rgba(10, 20, 24, 0.75);border:1px solid var(--c-border);box-shadow:0 4px 16px rgba(0,0,0,0.25)">
        <div class="flex items-center gap-2.5">
          <span class="flex items-center justify-center w-7 h-7 rounded-lg shrink-0" style="background:linear-gradient(135deg,#0694a2,#a3e635)">
            <i class="fa-solid fa-list text-xs" style="color:#0b1316"></i>
          </span>
          <div class="flex flex-col leading-tight">
            <span class="text-sm font-bold" style="color:#e5e7eb">
              {{ visible.length }}<span v-if="filter !== 'all'"> of {{ data.length }}</span>
              <span style="color:var(--c-muted);font-weight:500"> {{ filter === 'ok' ? 'OK URLs' : filter === 'errors' ? 'errored URLs' : 'URLs found' }}</span>
            </span>
            <span class="text-[10px] uppercase tracking-wider" style="color:var(--c-muted)">
              {{ filter !== 'all' ? 'Filter active · click tile to clear' : 'Click any link to open · use icons to copy' }}
            </span>
          </div>
        </div>
        <button
          type="button"
          @click="copyAll(visible)"
          class="flex items-center gap-1 text-[11px] font-semibold px-2 py-1 rounded-md transition-all hover:scale-[1.03] active:scale-[0.97]"
          :style="{
            background: copiedAll ? 'linear-gradient(135deg,#65a30d,#84cc16)' : 'linear-gradient(135deg,#0694a2,#16bdca)',
            color: copiedAll ? '#0b1316' : '#ffffff',
            boxShadow: copiedAll ? '0 0 0 1px #84cc1655, 0 2px 8px #65a30d33' : '0 0 0 1px #16bdca55, 0 2px 8px #0694a233',
          }"
        >
          <i :class="copiedAll ? 'fa-solid fa-check' : 'fa-solid fa-copy'" class="text-[11px]"></i>
          {{ copiedAll ? 'Copied!' : 'Copy All' }}
        </button>
      </div>
      <div v-for="(u, i) in visible" :key="i" class="flex items-center gap-2 rounded-xl px-3 py-2 text-xs" style="background:rgba(255,255,255,0.03);border:1px solid var(--c-border)">
        <a :href="u.url" target="_blank" rel="noopener noreferrer" class="font-mono truncate flex-1 hover:underline" style="color:var(--c-muted)">{{ u.url }}</a>
        <button
          type="button"
          @click="copyOne(u.url, i)"
          class="shrink-0 p-1 rounded transition-colors hover:bg-white/10"
          :style="{ color: copiedIdx === i ? '#65a30d' : 'var(--c-muted)' }"
          aria-label="Copy URL"
          title="Copy URL"
        >
          <i :class="copiedIdx === i ? 'fa-solid fa-check' : 'fa-solid fa-copy'" class="text-[13px]"></i>
        </button>
        <span class="text-[10px] font-bold px-2 py-0.5 rounded shrink-0" :style="{ background: `${statusColor(u.status)}20`, color: statusColor(u.status) }">{{ u.status === null ? '...' : u.status === 0 ? 'ERR' : u.status }}</span>
      </div>
    </div>
  </div>
</template>
