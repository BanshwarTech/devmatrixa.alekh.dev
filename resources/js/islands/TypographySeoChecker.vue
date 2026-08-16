<script setup>
import { ref } from 'vue';

const url = ref('');
const focused = ref(false);
const data = ref(null);
const loading = ref(false);
const error = ref('');

const statusColor = (s) => (s === 'ideal' ? '#65a30d' : s === 'acceptable' ? '#a3e635' : s === 'too-large' ? '#f97316' : s === 'too-small' ? '#f87171' : '#76d0d9');
const scoreColor = (score) => statusColor(score >= 80 ? 'ideal' : score >= 60 ? 'acceptable' : 'too-small');

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
    const res = await fetch('/api/typography-seo-checker', {
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
          <h2 class="font-sans text-2xl sm:text-3xl font-700 tracking-tight leading-tight">Enter a URL to audit typography</h2>
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
            <i class="fa-solid fa-font text-xs" style="color:#061c21"></i>
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
    <div class="glass rounded-3xl p-6 flex items-center gap-6">
      <div class="font-sans text-5xl font-700" :style="{ color: scoreColor(data.score) }">{{ data.score }}<span class="text-2xl" style="color:var(--c-muted)">/100</span></div>
      <div>
        <p class="text-xs uppercase tracking-widest font-semibold" style="color:var(--c-muted)">Typography Score</p>
        <p class="text-sm" style="color:var(--c-muted)">{{ data.issues.length }} issue(s) · {{ data.passes.length }} passed</p>
      </div>
    </div>

    <div class="glass rounded-3xl overflow-hidden">
      <div class="px-5 py-3" style="border-bottom:1px solid var(--c-border);background:rgba(255,255,255,0.06)">
        <p class="text-xs font-bold uppercase tracking-widest" style="color:var(--c-muted)">Per-element font sizes</p>
      </div>
      <div class="divide-y" style="border-color:var(--c-border)">
        <div v-for="e in data.elements" :key="e.tag" class="px-5 py-3 flex items-center justify-between gap-3 text-sm">
          <div class="flex items-center gap-3 min-w-0 flex-1">
            <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded" style="background:rgba(6,148,162,0.15);color:#0694a2">{{ e.tag }}</span>
            <span style="color:var(--c-text)">{{ e.label }}</span>
            <span v-if="e.count > 0" class="text-[10px]" style="color:var(--c-muted)">× {{ e.count }}</span>
          </div>
          <div class="text-right">
            <p class="font-mono text-sm font-semibold" :style="{ color: statusColor(e.status) }">{{ e.pxSize ? `${e.pxSize}px` : '—' }}</p>
            <p class="text-[10px]" style="color:var(--c-muted)">ideal {{ e.rec.ideal_min }}–{{ e.rec.ideal_max }}px</p>
          </div>
        </div>
      </div>
    </div>

    <div v-if="data.issues.length > 0" class="glass rounded-3xl p-5 space-y-3">
      <p class="text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--c-muted)">Issues</p>
      <div v-for="(i, idx) in data.issues" :key="idx" class="rounded-lg px-3 py-2.5" style="background:rgba(249,115,22,0.07);border:1px solid rgba(249,115,22,0.15)">
        <p class="text-xs font-semibold" style="color:var(--c-text)">{{ i.msg }}</p>
        <p class="text-[11px] mt-1 font-mono" style="color:var(--c-muted)"><span class="font-semibold not-italic" style="color:#0694a2">Fix: </span>{{ i.fix }}</p>
      </div>
    </div>

    <div v-if="data.passes.length > 0" class="glass rounded-3xl p-5">
      <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--c-muted)">{{ data.passes.length }} Passed</p>
      <div class="grid sm:grid-cols-2 gap-2">
        <div v-for="(p, i) in data.passes" :key="i" class="flex items-start gap-2 rounded-lg px-3 py-2 text-xs" style="background:rgba(163,230,53,0.07);border:1px solid rgba(163,230,53,0.15)">
          <i class="fa-solid fa-circle-check shrink-0 mt-0.5" style="color:#65a30d"></i>
          <p style="color:var(--c-text)">{{ p }}</p>
        </div>
      </div>
    </div>
  </div>
</template>
