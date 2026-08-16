<script setup>
import { ref } from 'vue';
import StatTile from './shared/StatTile.vue';

const url = ref('');
const focused = ref(false);
const data = ref(null);
const loading = ref(false);
const error = ref('');

async function analyze() {
  const u = url.value.trim();
  if (!u) {
    error.value = 'Please enter a URL.';
    return;
  }
  if (!/^https?:\/\/.+/.test(u)) {
    error.value = 'URL must start with http:// or https://';
    return;
  }
  error.value = '';
  data.value = null;
  loading.value = true;
  try {
    const res = await fetch('/api/script-audit', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ url: u, maxPages: 15 }),
    });
    const j = await res.json();
    if (!res.ok || j.error) error.value = j.error || 'Failed';
    else data.value = j;
  } catch {
    error.value = 'Network error.';
  }
  loading.value = false;
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
          <h2 class="font-sans text-2xl sm:text-3xl font-700 tracking-tight leading-tight">Enter a URL, we'll crawl up to 15 pages</h2>
        </div>
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold shrink-0 self-start" style="background:rgba(163,230,53,0.10);border:1px solid rgba(163,230,53,0.28);color:#65a30d">
          <i class="fa-solid fa-bolt text-xs"></i>
          Fast & free
        </div>
      </div>

      <div :class="['url-input-wrap', focused ? 'focused' : '']" class="flex gap-2 sm:gap-3 flex-col sm:flex-row p-1.5 rounded-2xl">
        <div class="relative flex-1">
          <i class="fa-solid fa-globe absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none text-sm" :style="{ color: focused ? '#0694a2' : 'var(--c-muted)' }"></i>
          <input
            v-model="url"
            type="url"
            @focus="focused = true"
            @blur="focused = false"
            @keydown.enter="!loading && analyze()"
            placeholder="https://example.com"
            spellcheck="false"
            autocapitalize="none"
            autocorrect="off"
            class="w-full pl-11 pr-11 py-3.5 rounded-xl text-sm bg-transparent outline-none"
            style="color:var(--c-text)"
          >
          <button v-if="url && !loading" type="button" @click="url = ''" aria-label="Clear input" class="absolute right-3 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full flex items-center justify-center transition-colors" style="background:rgba(6,148,162,0.10);color:var(--c-muted)">
            <i class="fa-solid fa-xmark text-xs"></i>
          </button>
        </div>
        <button @click="analyze" :disabled="loading" class="btn-primary px-7 py-3.5 rounded-xl text-sm font-700 inline-flex items-center justify-center gap-2 whitespace-nowrap disabled:opacity-70 disabled:cursor-not-allowed">
          <template v-if="loading">
            <span class="w-3.5 h-3.5 rounded-full border-2 border-current border-r-transparent animate-spin" style="color:#061c21"></span>
            Analyzing
          </template>
          <template v-else>
            <i class="fa-solid fa-code-branch text-xs" style="color:#061c21"></i>
            Audit
          </template>
        </button>
      </div>

      <div v-if="error" class="mt-5 rounded-2xl px-4 py-3 text-sm font-semibold flex items-start gap-3" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);color:#ef4444">
        <i class="fa-solid fa-triangle-exclamation mt-0.5 shrink-0"></i>
        <span class="leading-snug">{{ error }}</span>
      </div>

      <div class="mt-6 pt-5 flex flex-wrap items-center gap-x-6 gap-y-2.5 text-[11px] font-semibold" style="border-top:1px solid var(--c-border);color:var(--c-muted)">
        <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-bolt text-xs" style="color:#a3e635"></i>Lightning fast</span>
        <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-shield text-xs" style="color:#16bdca"></i>Privacy-first</span>
        <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-circle-check text-xs" style="color:#a3e635"></i>No signup</span>
        <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-infinity text-xs" style="color:#16bdca"></i>Unlimited use</span>
        <span class="inline-flex items-center gap-1.5 ml-auto">
          <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background:#65a30d"></span>
            <span class="relative inline-flex rounded-full h-2 w-2" style="background:#65a30d"></span>
          </span>
          <span style="color:#65a30d">Ready</span>
        </span>
      </div>
    </div>
  </div>

  <div v-if="data" class="space-y-4 tool-results">
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
      <StatTile :value="data.pagesScanned" label="Pages" color="#0694a2" />
      <StatTile :value="data.uniqueScripts" label="Unique Scripts" color="#0694a2" />
      <StatTile :value="data.totalRefs" label="Total Refs" color="#a3e635" />
      <StatTile :value="data.totalIssues" label="Issues" :color="data.totalIssues > 0 ? '#f97316' : '#65a30d'" />
    </div>

    <div v-if="data.issues.multiVersion.length > 0" class="glass rounded-3xl p-5">
      <p class="text-xs font-bold uppercase tracking-widest mb-3 inline-flex items-center gap-2" style="color:#f97316">
        <i class="fa-solid fa-triangle-exclamation text-xs"></i>Multiple Versions
      </p>
      <div class="space-y-3">
        <div v-for="(m, i) in data.issues.multiVersion" :key="i" class="rounded-xl p-3" style="background:rgba(249,115,22,0.07);border:1px solid rgba(249,115,22,0.15)">
          <p class="text-sm font-semibold mb-2" style="color:var(--c-text)">{{ m.library }}, {{ m.versionCount }} versions</p>
          <div v-for="(v, j) in m.versions" :key="j" class="flex items-center gap-2 text-xs ml-2 mt-1">
            <span class="font-mono px-2 py-0.5 rounded" style="background:rgba(6,148,162,0.10);color:#0694a2">v{{ v.version }}</span>
            <span class="truncate" style="color:var(--c-muted)">{{ v.url }}</span>
            <span class="text-[10px]" style="color:var(--c-muted)">· {{ v.pageCount }} pages</span>
          </div>
        </div>
      </div>
    </div>

    <div v-if="data.issues.pageDuplicates.length > 0" class="glass rounded-3xl p-5">
      <p class="text-xs font-bold uppercase tracking-widest mb-3 inline-flex items-center gap-2" style="color:#f97316">
        <i class="fa-solid fa-copy text-xs"></i>Same-page duplicates
      </p>
      <div class="space-y-2">
        <div v-for="(d, i) in data.issues.pageDuplicates" :key="i" class="text-xs rounded-lg px-3 py-2" style="background:rgba(249,115,22,0.07);border:1px solid rgba(249,115,22,0.15)">
          <p class="font-semibold truncate">{{ d.name }} <span class="text-[10px]" style="color:#f97316">× {{ d.count }}</span></p>
          <p class="text-[10px] truncate" style="color:var(--c-muted)">on {{ d.page }}</p>
        </div>
      </div>
    </div>

    <div class="glass rounded-3xl overflow-hidden">
      <div class="px-5 py-3" style="border-bottom:1px solid var(--c-border);background:rgba(255,255,255,0.06)">
        <p class="text-xs font-bold uppercase tracking-widest" style="color:var(--c-muted)">Per-page scripts</p>
      </div>
      <div class="p-4 space-y-2 max-h-[500px] overflow-y-auto">
        <details v-for="(p, i) in data.pages" :key="i" class="rounded-xl px-3 py-2" style="background:rgba(255,255,255,0.03);border:1px solid var(--c-border)">
          <summary class="cursor-pointer text-xs flex justify-between">
            <span class="truncate" style="color:var(--c-text)">{{ p.url }}</span>
            <span class="shrink-0 ml-2" style="color:#0694a2">{{ p.count }} scripts</span>
          </summary>
          <div class="mt-2 space-y-1 pl-3">
            <p v-for="(s, j) in p.scripts" :key="j" class="font-mono text-[10px] truncate" style="color:var(--c-muted)">{{ s.name }}</p>
          </div>
        </details>
      </div>
    </div>
  </div>
</template>
