<script setup>
import { ref, computed } from 'vue';
import StatTile from './shared/StatTile.vue';

const url = ref('');
const focused = ref(false);
const data = ref(null);
const loading = ref(false);
const error = ref('');

const typeColor = { JavaScript: '#f59e0b', CSS: '#0694a2', Image: '#a3e635', Media: '#16bdca' };

const pctOfTotal = computed(() => (size) => (data.value && data.value.totalSize > 0 ? (size / data.value.totalSize) * 100 : 0));

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
    const res = await fetch('/api/page-weight', {
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
          <h2 class="font-sans text-2xl sm:text-3xl font-700 tracking-tight leading-tight">Enter a URL to break down page weight.</h2>
          <p class="mt-2 text-sm sm:text-[15px] leading-relaxed max-w-xl" style="color:var(--c-muted)">Fast and free.</p>
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
            placeholder="Paste your page URL here"
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
            <i class="fa-solid fa-scale-balanced text-xs" style="color:#061c21"></i>
            Analyze
          </template>
        </button>
      </div>

      <div v-if="error" class="mt-5 rounded-2xl px-4 py-3 text-sm font-semibold flex items-start gap-3" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);color:#ef4444">
        <i class="fa-solid fa-triangle-exclamation mt-0.5 shrink-0"></i>
        <span class="leading-snug">{{ error }}</span>
      </div>

      <div class="mt-6 pt-5 flex flex-wrap items-center gap-x-6 gap-y-2.5 text-[11px] font-semibold" style="border-top:1px solid var(--c-border);color:var(--c-muted)">
        <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-bolt text-xs" style="color:#a3e635"></i>Lightning Fast</span>
        <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-shield text-xs" style="color:#16bdca"></i>Privacy-First</span>
        <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-circle-check text-xs" style="color:#a3e635"></i>No Signup</span>
        <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-infinity text-xs" style="color:#16bdca"></i>Unlimited Use</span>
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
      <StatTile :value="data.totalSizeF" label="Total Weight" color="#0694a2" />
      <StatTile :value="data.htmlSizeF" label="HTML" color="#0694a2" />
      <StatTile :value="data.resources.length" label="Resources" color="#a3e635" />
      <StatTile :value="data.typeSummary.length" label="Types" color="#16bdca" />
    </div>

    <div class="glass rounded-3xl p-5">
      <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--c-muted)">By type</p>
      <div class="space-y-2">
        <div v-for="t in data.typeSummary" :key="t.type">
          <div class="flex justify-between text-xs mb-1">
            <span style="color:var(--c-text)"><strong>{{ t.type }}</strong> · {{ t.count }} files</span>
            <span :style="{ color: typeColor[t.type] ?? '#0694a2' }">{{ t.sizeF }} ({{ pctOfTotal(t.size).toFixed(1) }}%)</span>
          </div>
          <div class="h-2 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.06)">
            <div class="h-full" :style="{ width: pctOfTotal(t.size) + '%', background: typeColor[t.type] ?? '#0694a2' }"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="glass rounded-3xl overflow-hidden">
      <div class="px-5 py-3" style="border-bottom:1px solid var(--c-border);background:rgba(255,255,255,0.06)">
        <p class="text-xs font-bold uppercase tracking-widest" style="color:var(--c-muted)">Largest resources</p>
      </div>
      <div class="p-4 space-y-2 max-h-[500px] overflow-y-auto">
        <a
          v-for="(r, i) in data.resources"
          :key="i"
          :href="r.url"
          target="_blank"
          rel="noopener noreferrer"
          class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs hover:translate-x-0.5 transition-transform"
          style="background:rgba(255,255,255,0.03);border:1px solid var(--c-border)"
        >
          <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded shrink-0" :style="{ background: (typeColor[r.type] ?? '#0694a2') + '20', color: typeColor[r.type] ?? '#0694a2' }">{{ r.type }}</span>
          <span class="font-mono truncate flex-1" style="color:var(--c-muted)">{{ r.name }}</span>
          <span class="font-semibold shrink-0" :style="{ color: typeColor[r.type] ?? '#0694a2' }">{{ r.sizeF }}</span>
        </a>
      </div>
    </div>
  </div>
</template>
