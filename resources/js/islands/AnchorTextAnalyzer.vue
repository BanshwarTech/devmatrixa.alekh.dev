<script setup>
import { ref, computed } from 'vue';
import StatTile from './shared/StatTile.vue';

const url = ref('');
const focused = ref(false);
const data = ref(null);
const loading = ref(false);
const error = ref('');
const filter = ref('all');

const typeBg = {
  keyword: { bg: 'rgba(101,163,13,0.12)', col: '#65a30d' },
  generic: { bg: 'rgba(249,115,22,0.12)', col: '#f97316' },
  naked_url: { bg: 'rgba(22,189,202,0.12)', col: '#16bdca' },
  image: { bg: 'rgba(6,148,162,0.12)', col: '#0694a2' },
  empty: { bg: 'rgba(248,113,113,0.12)', col: '#f87171' },
};

const filtered = computed(() => {
  if (!data.value) return [];
  return filter.value === 'all' ? data.value.anchors : data.value.anchors.filter((a) => a.type === filter.value);
});

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
  filter.value = 'all';
  loading.value = true;
  try {
    const res = await fetch('/api/anchor-text-analyzer', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ url: u }),
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
          <h2 class="font-sans text-2xl sm:text-3xl font-700 tracking-tight leading-tight">Audit anchor links on any URL</h2>
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
            <i class="fa-solid fa-link text-xs" style="color:#061c21"></i>
            Analyze Anchors
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

  <div v-if="data" class="space-y-6 tool-results">
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
      <StatTile :value="data.total" label="Total" color="#0694a2" />
      <StatTile :value="data.internal" label="Internal" color="#0694a2" />
      <StatTile :value="data.external" label="External" color="#7edce2" />
      <StatTile :value="data.nofollow" label="Nofollow" color="#f97316" />
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
      <div v-for="t in ['keyword', 'generic', 'naked_url', 'image', 'empty']" :key="t" class="glass rounded-2xl p-3 text-center">
        <p class="font-sans text-xl font-700" :style="{ color: typeBg[t].col }">{{ data.typeCounts[t] ?? 0 }}</p>
        <p class="text-[10px] uppercase tracking-widest font-semibold" style="color:var(--c-muted)">{{ t.replace('_', ' ') }}</p>
      </div>
    </div>

    <div v-if="data.issues.length > 0" class="glass rounded-3xl p-5 space-y-3">
      <p class="text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--c-muted)">Issues</p>
      <div v-for="(i, idx) in data.issues" :key="idx" class="rounded-lg px-3 py-2.5" style="background:rgba(249,115,22,0.07);border:1px solid rgba(249,115,22,0.15)">
        <p class="text-xs font-semibold" style="color:var(--c-text)">{{ i.msg }}</p>
        <p class="text-[11px] mt-1" style="color:var(--c-muted)"><span class="font-semibold" style="color:#0694a2">Fix: </span>{{ i.fix }}</p>
      </div>
    </div>

    <div v-if="Object.keys(data.topAnchors).length > 0" class="glass rounded-3xl p-5">
      <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--c-muted)">Top Keyword Anchors</p>
      <div class="flex flex-wrap gap-2">
        <span v-for="(n, t) in data.topAnchors" :key="t" class="text-xs px-3 py-1.5 rounded-full" style="background:rgba(6,148,162,0.10);border:1px solid rgba(6,148,162,0.22);color:var(--c-text)">
          {{ t }} <strong style="color:#0694a2">×{{ n }}</strong>
        </span>
      </div>
    </div>

    <div class="glass rounded-3xl overflow-hidden">
      <div class="px-5 py-4 flex flex-wrap items-center gap-2" style="border-bottom:1px solid var(--c-border);background:rgba(255,255,255,0.06)">
        <button
          v-for="f in ['all', 'keyword', 'generic', 'naked_url', 'image', 'empty']"
          :key="f"
          type="button"
          @click="filter = f"
          class="cpill px-3 py-1.5 rounded-full text-xs"
          :class="{ active: filter === f }"
        >{{ f }}</button>
      </div>
      <div class="p-4 space-y-2 max-h-[600px] overflow-y-auto">
        <a
          v-for="(a, i) in filtered.slice(0, 200)"
          :key="i"
          :href="a.href"
          target="_blank"
          rel="noopener noreferrer"
          class="flex items-start gap-3 rounded-xl px-3 py-2 text-sm hover:translate-x-0.5 transition-transform"
          style="background:rgba(255,255,255,0.03);border:1px solid var(--c-border)"
        >
          <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded shrink-0" :style="{ background: (typeBg[a.type] ?? typeBg.keyword).bg, color: (typeBg[a.type] ?? typeBg.keyword).col }">{{ a.type }}</span>
          <div class="flex-1 min-w-0">
            <p class="truncate font-medium" style="color:var(--c-text)">{{ a.text }}</p>
            <p class="text-[10px] truncate font-mono" style="color:var(--c-muted)">{{ a.href }}</p>
          </div>
          <div class="flex gap-1 shrink-0">
            <span v-if="a.nofollow" class="text-[9px] px-1.5 py-0.5 rounded" style="background:rgba(249,115,22,0.15);color:#f97316">nofollow</span>
            <span v-if="a.duplicate" class="text-[9px] px-1.5 py-0.5 rounded" style="background:rgba(248,113,113,0.15);color:#f87171">dup</span>
            <span v-if="!a.internal" class="text-[9px] px-1.5 py-0.5 rounded" style="background:rgba(126,220,226,0.15);color:#16bdca">ext</span>
          </div>
        </a>
        <p v-if="filtered.length > 200" class="text-xs text-center pt-2" style="color:var(--c-muted)">+{{ filtered.length - 200 }} more…</p>
      </div>
    </div>
  </div>
</template>
