<script setup>
import { ref } from 'vue';
import StatTile from './shared/StatTile.vue';

const url = ref('');
const focused = ref(false);
const data = ref(null);
const loading = ref(false);
const error = ref('');
const copiedFinal = ref(false);
const copiedHop = ref(null);

function statusColor(s) {
  if (s === -1) return '#f87171';
  if (s === 0) return '#a3a3a3';
  if (s >= 200 && s < 300) return '#65a30d';
  if (s >= 300 && s < 400) return '#f59e0b';
  if (s >= 400 && s < 500) return '#f87171';
  if (s >= 500) return '#dc2626';
  return '#a3a3a3';
}

const issueColor = (t) => (t === 'error' ? '#f87171' : t === 'warning' ? '#f59e0b' : '#16bdca');
const issueIcon = (t) => (t === 'error' ? 'fa-solid fa-circle-xmark' : t === 'warning' ? 'fa-solid fa-triangle-exclamation' : 'fa-solid fa-circle-info');

async function analyze() {
  const trimmed = url.value.trim();
  if (!trimmed) { error.value = 'Please enter a URL.'; return; }
  if (!/^https?:\/\/.+/.test(trimmed)) { error.value = 'URL must start with http:// or https://'; return; }
  error.value = '';
  data.value = null;
  loading.value = true;
  try {
    const res = await fetch('/api/redirect-chain', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ url: trimmed }),
    });
    const j = await res.json();
    if (!res.ok || j.error) error.value = j.error || 'Failed';
    else data.value = j;
  } catch {
    error.value = 'Network error.';
  }
  loading.value = false;
}

async function copy(text, onSet) {
  try {
    await navigator.clipboard.writeText(text);
    onSet(true);
    setTimeout(() => onSet(false), 1500);
  } catch { /* ignore */ }
}

function copyHop(hopUrl, idx) {
  copy(hopUrl, () => {});
  copiedHop.value = idx;
  setTimeout(() => { if (copiedHop.value === idx) copiedHop.value = null; }, 1500);
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
          <h2 class="font-sans text-2xl sm:text-3xl font-700 tracking-tight leading-tight">Enter a URL to trace.</h2>
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
            @keydown.enter="!loading && analyze()"
            placeholder="Paste your page URL here"
            spellcheck="false"
            autocapitalize="none"
            autocorrect="off"
            class="w-full pl-11 pr-5 py-3.5 rounded-xl text-sm bg-transparent outline-none"
            style="color:var(--c-text)"
          >
        </div>
        <button @click="analyze" :disabled="loading" class="btn-primary px-7 py-3.5 rounded-xl text-sm font-700 inline-flex items-center justify-center gap-2 whitespace-nowrap disabled:opacity-70 disabled:cursor-not-allowed">
          <template v-if="loading">
            <span class="w-3.5 h-3.5 rounded-full border-2 border-current border-r-transparent animate-spin" style="color:#061c21"></span>
            Analyzing
          </template>
          <template v-else>
            <i class="fa-solid fa-rotate text-xs"></i>
            Trace
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
      <StatTile :value="data.hopCount" label="Total Hops" color="#0694a2" />
      <StatTile :value="data.redirectCount" label="Redirects" color="#f59e0b" />
      <StatTile :value="`${data.totalTimeMs} ms`" label="Total Time" color="#0694a2" />
      <StatTile :value="data.finalStatus || '—'" label="Final Status" :color="statusColor(data.finalStatus)" />
    </div>

    <div class="glass rounded-3xl p-4 sm:p-5">
      <div class="flex items-center justify-between gap-3 mb-3">
        <div class="text-[11px] uppercase tracking-widest font-semibold" style="color:var(--c-muted)">Final destination</div>
        <button
          type="button"
          @click="copy(data.finalUrl, (v) => (copiedFinal = v))"
          class="flex items-center gap-1.5 text-[11px] font-semibold px-2.5 py-1.5 rounded-lg transition-colors"
          :style="{ background: copiedFinal ? '#65a30d22' : 'rgba(255,255,255,0.06)', color: copiedFinal ? '#65a30d' : '#e5e7eb', border: '1px solid var(--c-border)' }"
        >
          <i :class="copiedFinal ? 'fa-solid fa-check' : 'fa-solid fa-copy'" class="text-[12px]"></i>
          {{ copiedFinal ? 'Copied!' : 'Copy Final URL' }}
        </button>
      </div>
      <a :href="data.finalUrl" target="_blank" rel="noopener noreferrer" class="font-mono text-sm break-all hover:underline" style="color:#e5e7eb">{{ data.finalUrl }}</a>
    </div>

    <div class="space-y-2">
      <div v-for="(h, idx) in data.hops" :key="idx">
        <div class="glass rounded-2xl p-4 sm:p-5 relative" :style="{ borderLeft: `3px solid ${statusColor(h.status)}` }">
          <div class="flex flex-wrap items-start gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-xl font-sans font-700 text-sm shrink-0" :style="{ background: `${statusColor(h.status)}1f`, color: statusColor(h.status) }">
              {{ idx + 1 }}
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex flex-wrap items-center gap-2 mb-1.5">
                <span class="text-[11px] font-bold px-2 py-0.5 rounded" :style="{ background: `${statusColor(h.status)}22`, color: statusColor(h.status) }">
                  {{ h.status === -1 ? 'LOOP' : h.status === 0 ? 'ERR' : h.status }}
                </span>
                <span v-if="h.statusText" class="text-[11px]" style="color:var(--c-muted)">{{ h.statusText }}</span>
                <span class="text-[10px] inline-flex items-center gap-1 font-semibold px-1.5 py-0.5 rounded" :style="{ background: h.protocol === 'https:' ? '#65a30d22' : '#f8717122', color: h.protocol === 'https:' ? '#65a30d' : '#f87171' }">
                  <i :class="h.protocol === 'https:' ? 'fa-solid fa-lock' : 'fa-solid fa-unlock'" class="text-[9px]"></i>
                  {{ h.protocol.replace(':', '').toUpperCase() }}
                </span>
                <span class="text-[10px] inline-flex items-center gap-1 ml-auto font-semibold" style="color:var(--c-muted)">
                  <i class="fa-solid fa-clock text-[10px]"></i> {{ h.timeMs }} ms
                </span>
              </div>
              <div class="flex items-start gap-2">
                <a :href="h.url" target="_blank" rel="noopener noreferrer" class="flex-1 font-mono text-[12.5px] break-all hover:underline min-w-0" style="color:#e5e7eb">{{ h.url }}</a>
                <button
                  type="button"
                  @click="copyHop(h.url, idx)"
                  class="shrink-0 p-1 rounded transition-colors hover:bg-white/10"
                  :style="{ color: copiedHop === idx ? '#65a30d' : 'var(--c-muted)' }"
                  aria-label="Copy hop URL"
                  title="Copy hop URL"
                >
                  <i :class="copiedHop === idx ? 'fa-solid fa-check' : 'fa-solid fa-copy'" class="text-[12px]"></i>
                </button>
              </div>
              <div v-if="h.location" class="mt-2 flex items-center gap-2 text-[11px]" style="color:var(--c-muted)">
                <i class="fa-solid fa-circle-arrow-down" style="color:#f59e0b"></i>
                <span class="font-semibold">Location:</span>
                <span class="font-mono break-all">{{ h.location }}</span>
              </div>
              <div v-if="h.server || h.contentType" class="mt-1.5 flex flex-wrap gap-x-3 gap-y-0.5 text-[10.5px] font-mono" style="color:var(--c-muted)">
                <span v-if="h.server">server: {{ h.server }}</span>
                <span v-if="h.contentType">content-type: {{ h.contentType.split(';')[0] }}</span>
              </div>
            </div>
          </div>
        </div>
        <div v-if="idx !== data.hops.length - 1" class="flex justify-center py-1">
          <i class="fa-solid fa-circle-arrow-down" style="color:#f59e0b"></i>
        </div>
      </div>
    </div>

    <div v-if="data.issues.length > 0" class="glass rounded-3xl p-5 space-y-3">
      <div class="flex items-center gap-2 mb-1">
        <i class="fa-solid fa-triangle-exclamation" style="color:#f59e0b"></i>
        <h4 class="font-sans font-700 text-base tracking-tight">Issues Found</h4>
        <span class="text-[10px] font-bold px-2 py-0.5 rounded ml-1" style="background:rgba(255,255,255,0.06);color:var(--c-muted)">{{ data.issues.length }}</span>
      </div>
      <div v-for="(iss, i) in data.issues" :key="i" class="rounded-xl p-3" :style="{ background: `${issueColor(iss.type)}10`, border: `1px solid ${issueColor(iss.type)}30` }">
        <div class="flex items-start gap-2.5">
          <i :class="issueIcon(iss.type)" class="shrink-0 mt-0.5" :style="{ color: issueColor(iss.type) }"></i>
          <div class="flex-1 min-w-0">
            <p class="text-[13px] font-semibold mb-1" style="color:#e5e7eb">{{ iss.msg }}</p>
            <p class="text-[12px] leading-relaxed" style="color:var(--c-muted)">{{ iss.fix }}</p>
          </div>
        </div>
      </div>
    </div>

    <div v-if="data.issues.length === 0" class="glass rounded-2xl p-4 flex items-center gap-3" style="background:rgba(101,163,13,0.06);border:1px solid rgba(101,163,13,0.25)">
      <i class="fa-solid fa-circle-check text-xl" style="color:#65a30d"></i>
      <div class="text-sm" style="color:#e5e7eb">
        No issues detected. The redirect chain is clean — no downgrades, loops, or excessive hops.
      </div>
    </div>
  </div>
</template>
