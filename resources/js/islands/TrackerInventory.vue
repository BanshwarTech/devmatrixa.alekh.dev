<script setup>
import { ref, computed } from 'vue';
import StatTile from './shared/StatTile.vue';

const url = ref('');
const focused = ref(false);
const data = ref(null);
const loading = ref(false);
const error = ref('');
const filter = ref('all');
const copiedAll = ref(false);

const CAT_META = {
  analytics: { label: 'Analytics', color: '#0694a2', icon: 'fa-solid fa-chart-bar' },
  advertising: { label: 'Advertising', color: '#f87171', icon: 'fa-solid fa-bullhorn' },
  social: { label: 'Social', color: '#16bdca', icon: 'fa-solid fa-share-nodes' },
  'tag-manager': { label: 'Tag Manager', color: '#f59e0b', icon: 'fa-solid fa-tag' },
  chat: { label: 'Chat / Support', color: '#a3e635', icon: 'fa-solid fa-comment-dots' },
  video: { label: 'Video', color: '#f97316', icon: 'fa-solid fa-video' },
  fonts: { label: 'Fonts', color: '#7edce2', icon: 'fa-solid fa-font' },
  cdn: { label: 'CDN', color: '#0694a2', icon: 'fa-solid fa-server' },
  maps: { label: 'Maps', color: '#16bdca', icon: 'fa-solid fa-map' },
  payment: { label: 'Payment', color: '#65a30d', icon: 'fa-solid fa-credit-card' },
  captcha: { label: 'Captcha', color: '#a3e635', icon: 'fa-solid fa-shield-halved' },
  'error-tracking': { label: 'Error Tracking', color: '#16bdca', icon: 'fa-solid fa-bug' },
  'ab-testing': { label: 'A/B Testing', color: '#0694a2', icon: 'fa-solid fa-flask' },
  'session-replay': { label: 'Session Replay', color: '#f87171', icon: 'fa-solid fa-film' },
  other: { label: 'Other', color: '#a3a3a3', icon: 'fa-solid fa-circle-question' },
};

const PRIVACY_META = {
  high: { label: 'High Impact', color: '#f87171' },
  medium: { label: 'Medium', color: '#f59e0b' },
  low: { label: 'Low', color: '#65a30d' },
  unknown: { label: 'Unknown', color: '#a3a3a3' },
};

async function analyze() {
  const trimmed = url.value.trim();
  if (!trimmed) {
    error.value = 'Please enter a URL.';
    return;
  }
  if (!/^https?:\/\/.+/.test(trimmed)) {
    error.value = 'URL must start with http:// or https://';
    return;
  }
  error.value = '';
  data.value = null;
  loading.value = true;
  try {
    const res = await fetch('/api/tracker-inventory', {
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

const visibleDomains = computed(() => {
  if (!data.value) return [];
  return filter.value === 'all' ? data.value.domains : data.value.domains.filter((d) => d.category === filter.value);
});

const activeCats = computed(() => {
  if (!data.value) return [];
  return Object.keys(data.value.byCategory).filter((k) => data.value.byCategory[k] > 0);
});

async function copyAll() {
  if (visibleDomains.value.length === 0) return;
  try {
    await navigator.clipboard.writeText(visibleDomains.value.map((d) => d.host).join('\n'));
    copiedAll.value = true;
    setTimeout(() => {
      copiedAll.value = false;
    }, 1800);
  } catch {
    // ignore
  }
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
          <h2 class="font-sans text-2xl sm:text-3xl font-700 tracking-tight leading-tight">Enter a URL to scan.</h2>
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
            <i class="fa-solid fa-satellite-dish text-xs"></i>
            Scan
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
      <StatTile :value="data.totalRequests" label="Third-party requests" color="#16bdca" />
      <StatTile :value="data.uniqueDomains" label="Unique domains" color="#0694a2" />
      <StatTile :value="data.knownTrackers" label="Known trackers" color="#f59e0b" />
      <StatTile :value="data.highPrivacyCount" label="High-privacy" color="#f87171" />
    </div>

    <div v-if="activeCats.length > 0" class="glass rounded-3xl p-4 sm:p-5">
      <div class="flex items-center justify-between gap-3 mb-3">
        <div class="text-[11px] uppercase tracking-widest font-semibold" style="color:var(--c-muted)">Filter by category</div>
        <button v-if="filter !== 'all'" type="button" @click="filter = 'all'" class="text-[11px] font-semibold underline" style="color:var(--c-muted)">Clear</button>
      </div>
      <div class="flex flex-wrap gap-2">
        <button
          type="button"
          @click="filter = 'all'"
          class="text-xs font-semibold px-3 py-1.5 rounded-full transition-colors"
          :style="{ background: filter === 'all' ? 'rgba(6,148,162,0.18)' : 'rgba(255,255,255,0.05)', color: filter === 'all' ? '#0694a2' : 'var(--c-text)', border: `1px solid ${filter === 'all' ? '#0694a255' : 'var(--c-border)'}` }"
        >
          All <span style="color:var(--c-muted)">{{ data.uniqueDomains }}</span>
        </button>
        <button
          v-for="cat in activeCats"
          :key="cat"
          type="button"
          @click="filter = cat"
          class="text-xs font-semibold px-3 py-1.5 rounded-full transition-colors inline-flex items-center gap-1.5"
          :style="{ background: filter === cat ? `${CAT_META[cat].color}1f` : 'rgba(255,255,255,0.05)', color: filter === cat ? CAT_META[cat].color : 'var(--c-text)', border: `1px solid ${filter === cat ? CAT_META[cat].color + '55' : 'var(--c-border)'}` }"
        >
          <i :class="CAT_META[cat].icon" class="text-[11px]"></i>
          {{ CAT_META[cat].label }} <span :style="{ color: filter === cat ? CAT_META[cat].color : 'var(--c-muted)', opacity: 0.85 }">{{ data.byCategory[cat] }}</span>
        </button>
      </div>
    </div>

    <div class="glass rounded-3xl p-4 sm:p-5">
      <div class="flex items-center justify-between gap-2 mb-3">
        <div class="text-[11px] uppercase tracking-widest font-semibold" style="color:var(--c-muted)">
          {{ visibleDomains.length }} {{ filter === 'all' ? 'domain' : CAT_META[filter].label.toLowerCase() + ' domain' }}{{ visibleDomains.length !== 1 ? 's' : '' }}
        </div>
        <button
          type="button"
          @click="copyAll"
          :disabled="visibleDomains.length === 0"
          class="flex items-center gap-1.5 text-[11px] font-semibold px-2.5 py-1.5 rounded-lg transition-colors disabled:opacity-40"
          :style="{ background: copiedAll ? '#65a30d22' : 'rgba(255,255,255,0.06)', color: copiedAll ? '#65a30d' : 'var(--c-text)', border: '1px solid var(--c-border)' }"
        >
          <i :class="copiedAll ? 'fa-solid fa-check' : 'fa-solid fa-copy'" class="text-[11px]"></i>
          {{ copiedAll ? 'Copied!' : 'Copy Hosts' }}
        </button>
      </div>

      <div v-if="visibleDomains.length === 0" class="text-center py-8 text-sm" style="color:var(--c-muted)">No domains in this category.</div>

      <div v-else class="space-y-2">
        <div v-for="d in visibleDomains" :key="d.host" class="rounded-xl p-3" style="background:rgba(255,255,255,0.03);border:1px solid var(--c-border)">
          <div class="flex flex-wrap items-start gap-3">
            <div class="flex items-center justify-center w-9 h-9 rounded-xl shrink-0" :style="{ background: `${CAT_META[d.category].color}1f`, color: CAT_META[d.category].color }">
              <i :class="CAT_META[d.category].icon" class="text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex flex-wrap items-center gap-2 mb-1">
                <span class="font-mono text-[13px] font-700 break-all" style="color:var(--c-text)">{{ d.host }}</span>
                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded" :style="{ background: `${CAT_META[d.category].color}22`, color: CAT_META[d.category].color }">{{ CAT_META[d.category].label }}</span>
                <span v-if="d.privacy !== 'unknown'" class="text-[9px] font-bold px-1.5 py-0.5 rounded" :style="{ background: `${PRIVACY_META[d.privacy].color}22`, color: PRIVACY_META[d.privacy].color }">{{ PRIVACY_META[d.privacy].label }}</span>
                <span v-if="d.tracker" class="text-[10px] font-semibold" style="color:var(--c-muted)">{{ d.tracker.name }}</span>
                <span class="ml-auto text-[10px] font-mono" style="color:var(--c-muted)">{{ d.count }} req</span>
              </div>
              <div class="flex flex-wrap gap-x-3 gap-y-0.5 text-[10.5px] font-mono mb-1.5" style="color:var(--c-muted)">
                <span v-for="(v, k) in d.types" :key="k">{{ k }}: {{ v }}</span>
              </div>
              <a :href="d.sample" target="_blank" rel="noopener noreferrer" class="text-[11px] inline-flex items-center gap-1 hover:underline" style="color:#16bdca">
                sample request <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
