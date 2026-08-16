<script setup>
import { ref, computed } from 'vue';
import StatTile from './shared/StatTile.vue';

const url = ref('');
const focused = ref(false);
const data = ref(null);
const loading = ref(false);
const error = ref('');
const bucket = ref('missing');
const copiedAll = ref(false);

const bucketMeta = {
  both: { label: 'Healthy (in both)', color: '#65a30d', hint: 'These pages are linked AND in your sitemap. No action needed.', icon: 'fa-solid fa-circle-check' },
  orphan: { label: 'Orphan (sitemap only)', color: '#f59e0b', hint: 'Listed in sitemap but no internal link reaches them. Add internal links or remove from sitemap.', icon: 'fa-solid fa-link-slash' },
  missing: { label: 'Missing from sitemap', color: '#f87171', hint: 'Linked across the site but absent from sitemap.xml. Add them so search engines discover them faster.', icon: 'fa-solid fa-file-circle-xmark' },
};

const list = computed(() => {
  if (!data.value) return [];
  if (bucket.value === 'both') return data.value.inBoth;
  if (bucket.value === 'orphan') return data.value.orphanInSitemap;
  return data.value.missingFromSitemap;
});

const meta = computed(() => bucketMeta[bucket.value]);

const coverageColor = computed(() => {
  if (!data.value) return '#65a30d';
  return data.value.coverage >= 90 ? '#65a30d' : data.value.coverage >= 70 ? '#f59e0b' : '#f87171';
});

async function analyze() {
  const trimmed = url.value.trim();
  if (!trimmed) { error.value = 'Please enter a URL.'; return; }
  if (!/^https?:\/\/.+/.test(trimmed)) { error.value = 'URL must start with http:// or https://'; return; }
  error.value = '';
  data.value = null;
  loading.value = true;
  try {
    const res = await fetch('/api/sitemap-diff', {
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

async function copyList() {
  if (list.value.length === 0) return;
  try {
    await navigator.clipboard.writeText(list.value.join('\n'));
    copiedAll.value = true;
    setTimeout(() => { copiedAll.value = false; }, 1800);
  } catch { /* ignore */ }
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
          <h2 class="font-sans text-2xl sm:text-3xl font-700 tracking-tight leading-tight">Enter your homepage URL.</h2>
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
            placeholder="https://example.com"
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
            <i class="fa-solid fa-code-compare text-xs"></i>
            Run Diff
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
    <div class="glass rounded-3xl p-5 sm:p-6 flex flex-col sm:flex-row items-center gap-5">
      <div class="relative flex items-center justify-center w-28 h-28 sm:w-32 sm:h-32 rounded-2xl shrink-0" :style="{ background: 'linear-gradient(135deg, #0694a222, #a3e63522)', border: `2px solid ${coverageColor}`, boxShadow: `0 0 32px ${coverageColor}33` }">
        <span class="font-sans font-700 text-4xl sm:text-5xl tracking-tighter" :style="{ color: coverageColor }">{{ data.coverage }}%</span>
      </div>
      <div class="flex-1 text-center sm:text-left">
        <div class="text-[11px] uppercase tracking-widest font-semibold mb-1" style="color:var(--c-muted)">Coverage</div>
        <div class="font-sans text-xl sm:text-2xl font-700 tracking-tight mb-2">
          {{ data.inBothCount }} of {{ data.inBothCount + data.orphanCount + data.missingCount }} unique pages in both
        </div>
        <div class="text-xs break-all" style="color:var(--c-muted)">
          <template v-if="data.sitemapsFound.length > 0">Sitemaps found: <span class="font-mono" style="color:#e5e7eb">{{ data.sitemapsFound.length }}</span> · </template>
          <span v-else style="color:#f87171">No sitemap discovered. · </span>
          Crawled: <span class="font-mono" style="color:#e5e7eb">{{ data.crawledCount }}</span>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-3 gap-3">
      <StatTile :value="data.inBothCount" label="In Both" color="#65a30d" clickable :active="bucket === 'both'" @click="bucket = 'both'" />
      <StatTile :value="data.orphanCount" label="Orphan" color="#f59e0b" clickable :active="bucket === 'orphan'" @click="bucket = 'orphan'" />
      <StatTile :value="data.missingCount" label="Missing" color="#f87171" clickable :active="bucket === 'missing'" @click="bucket = 'missing'" />
    </div>

    <div class="glass rounded-3xl p-4 sm:p-5">
      <div class="flex flex-wrap items-start justify-between gap-3 mb-3">
        <div class="flex items-center gap-2.5">
          <span class="flex items-center justify-center w-9 h-9 rounded-xl shrink-0" :style="{ background: `${meta.color}22`, color: meta.color }">
            <i :class="meta.icon"></i>
          </span>
          <div>
            <div class="font-sans font-700 text-sm tracking-tight" style="color:#e5e7eb">
              {{ meta.label }} <span style="color:var(--c-muted);font-weight:500">· {{ list.length }}</span>
            </div>
            <div class="text-[11px] max-w-xl" style="color:var(--c-muted)">{{ meta.hint }}</div>
          </div>
        </div>
        <button
          type="button"
          @click="copyList"
          :disabled="list.length === 0"
          class="flex items-center gap-1.5 text-[11px] font-semibold px-2.5 py-1.5 rounded-lg transition-colors disabled:opacity-40"
          :style="{ background: copiedAll ? '#65a30d22' : 'rgba(255,255,255,0.06)', color: copiedAll ? '#65a30d' : '#e5e7eb', border: '1px solid var(--c-border)' }"
        >
          <i :class="copiedAll ? 'fa-solid fa-check' : 'fa-solid fa-copy'" class="text-[12px]"></i>
          {{ copiedAll ? 'Copied!' : 'Copy List' }}
        </button>
      </div>

      <div v-if="list.length === 0" class="text-center py-8 text-sm" style="color:var(--c-muted)">
        <i class="fa-solid fa-circle-check text-2xl block mb-2" style="color:#65a30d"></i>
        Nothing in this bucket — clean on this dimension.
      </div>
      <div v-else class="space-y-1.5 max-h-[500px] overflow-y-auto">
        <a v-for="(u, i) in list" :key="i" :href="u" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 rounded-xl px-3 py-2 text-xs hover:bg-white/5 transition-colors" style="background:rgba(255,255,255,0.03);border:1px solid var(--c-border)">
          <span class="font-mono truncate flex-1" style="color:var(--c-muted)">{{ u }}</span>
        </a>
      </div>
    </div>

    <div v-if="data.sitemapsFound.length > 0" class="glass rounded-2xl p-4">
      <div class="text-[11px] uppercase tracking-widest font-semibold mb-2" style="color:var(--c-muted)">Sitemaps discovered</div>
      <div class="space-y-1">
        <a v-for="(sm, i) in data.sitemapsFound" :key="i" :href="sm" target="_blank" rel="noopener noreferrer" class="block font-mono text-[12px] hover:underline" style="color:#e5e7eb">
          {{ sm }}
        </a>
      </div>
    </div>
  </div>
</template>
