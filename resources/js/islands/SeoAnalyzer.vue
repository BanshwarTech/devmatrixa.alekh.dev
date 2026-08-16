<script setup>
import { ref } from 'vue';

const url = ref('');
const focused = ref(false);
const loading = ref(false);
const error = ref('');
const data = ref(null);

const examples = ['vercel.com', 'stripe.com', 'github.com'];
const signals = [
  'Title tag', 'Meta description', 'Headings (H1-H3)', 'Open Graph', 'Twitter Cards',
  'Schema.org', 'Canonical', 'ALT text', 'Robots', 'Viewport', 'Favicon', 'Page weight',
];

function useExample(ex) {
  url.value = /^https?:\/\//i.test(ex) ? ex : `https://${ex}`;
}

function scoreColor(s) { return s >= 80 ? '#65a30d' : s >= 60 ? '#f59e0b' : '#ef4444'; }
function scoreLabel(s) { return s >= 80 ? 'Excellent' : s >= 60 ? 'Needs Work' : 'Poor'; }

async function analyze() {
  const trimmed = url.value.trim();
  if (!trimmed) { error.value = 'Please enter a URL.'; return; }
  if (!/^https?:\/\/.+/.test(trimmed)) { error.value = 'Please enter a valid URL starting with http:// or https://'; return; }

  error.value = '';
  data.value = null;
  loading.value = true;
  try {
    const res = await fetch('/api/seo-analyzer', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ url: trimmed }),
    });
    const j = await res.json();
    if (!res.ok || j.error) error.value = j.error || 'Analysis failed. Please try again.';
    else data.value = j;
  } catch {
    error.value = 'Network error. Please try again.';
  }
  loading.value = false;
}

function circumference() { return Math.round(2 * Math.PI * 52); }
function dashOffset(score) { return Math.round(circumference() * (1 - score / 100)); }

const titleStatus = (d) => (d.title && d.title.length >= 30 && d.title.length <= 70 ? 'ok' : d.title ? 'warn' : 'err');
const descStatus = (d) => (d.metaDesc && d.metaDesc.length >= 70 && d.metaDesc.length <= 170 ? 'ok' : d.metaDesc ? 'warn' : 'err');
const dotColor = (s) => (s === 'ok' ? '#65a30d' : s === 'warn' ? '#f59e0b' : '#f87171');

function hostOf(u) {
  try { return new URL(u).hostname; } catch { return u; }
}

const statCards = (d) => ([
  { icon: 'fa-solid fa-clock', value: `${d.loadTime}ms`, label: 'Load Time', color: d.loadTime > 3000 ? '#f87171' : '#65a30d' },
  { icon: 'fa-solid fa-file-code', value: `${d.pageSize} KB`, label: 'Page Size', color: '#0694a2' },
  { icon: 'fa-solid fa-pen', value: d.wordCount.toLocaleString(), label: 'Word Count', color: '#a3e635' },
  { icon: 'fa-solid fa-image', value: d.totalImages, label: 'Images', color: '#16bdca' },
  { icon: 'fa-solid fa-link', value: d.internalLinks, label: 'Internal Links', color: '#0694a2' },
  { icon: 'fa-solid fa-arrow-up-right-from-square', value: d.externalLinks, label: 'External Links', color: '#7edce2' },
]);
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
          <h2 class="font-sans text-2xl sm:text-3xl font-700 tracking-tight leading-tight">Enter a URL to audit</h2>
          <p class="mt-2 text-sm sm:text-[15px] leading-relaxed max-w-xl" style="color:var(--c-muted)">We'll fetch the HTML, parse 10+ on-page SEO signals, and surface every issue with a clear, actionable fix.</p>
        </div>
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold shrink-0 self-start" style="background:rgba(163,230,53,0.10);border:1px solid rgba(163,230,53,0.28);color:#65a30d">
          <i class="fa-solid fa-bolt text-xs"></i>
          10+ signals checked
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
            <i class="fa-solid fa-magnifying-glass-chart text-xs"></i>
            Analyze
          </template>
        </button>
      </div>

      <div class="mt-5 flex flex-wrap items-center gap-2">
        <span class="text-[10px] font-bold uppercase tracking-widest mr-1 inline-flex items-center gap-1.5" style="color:var(--c-muted)">
          <i class="fa-solid fa-wand-magic-sparkles text-xs" style="color:#0694a2"></i>
          Try
        </span>
        <button
          v-for="ex in examples"
          :key="ex"
          type="button"
          @click="useExample(ex)"
          :disabled="loading"
          class="url-example-chip text-xs font-semibold px-3 py-1.5 rounded-full inline-flex items-center gap-1.5 transition-all disabled:opacity-50"
        >
          <i class="fa-solid fa-arrow-up-right-from-square text-[11px]" style="color:#0694a2"></i>
          {{ ex }}
        </button>
      </div>

      <div v-if="error" class="mt-5 rounded-2xl px-4 py-3 text-sm font-semibold flex items-start gap-3" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);color:#ef4444">
        <i class="fa-solid fa-triangle-exclamation mt-0.5 shrink-0"></i>
        <span class="leading-snug">{{ error }}</span>
      </div>

      <div class="mt-7 pt-6" style="border-top:1px dashed var(--c-border)">
        <div class="flex items-center gap-2 mb-3">
          <i class="fa-solid fa-list-check text-xs" style="color:#16bdca"></i>
          <span class="text-[10px] font-bold uppercase tracking-widest" style="color:var(--c-muted)">What we check</span>
          <span class="ml-auto text-[10px] font-semibold" style="color:#0694a2">{{ signals.length }} signals</span>
        </div>
        <div class="flex flex-wrap gap-1.5">
          <span v-for="s in signals" :key="s" class="url-signal-chip text-[11px] font-semibold px-2.5 py-1 rounded-md inline-flex items-center gap-1.5">
            <i class="fa-solid fa-check text-[10px]" style="color:#65a30d"></i>
            {{ s }}
          </span>
        </div>
      </div>
    </div>
  </div>

  <div v-if="loading" class="glass rounded-3xl p-12 text-center mb-8 relative overflow-hidden">
    <div class="absolute top-0 left-1/4 right-1/4 h-[2px] rounded-b-full" style="background:linear-gradient(90deg, transparent, #a3e635, #0694a2, transparent)"></div>
    <div class="w-16 h-16 rounded-full mx-auto mb-6 flex items-center justify-center animate-spin" style="background:linear-gradient(135deg,#a3e635,#0694a2);box-shadow:0 14px 32px rgba(163,230,53,0.35)">
      <i class="fa-solid fa-magnifying-glass-chart" style="color:#061c21"></i>
    </div>
    <p class="font-sans text-lg font-700 mb-2">Analyzing your page...</p>
    <p class="text-sm" style="color:var(--c-muted)">Fetching HTML, scanning signals, calculating score</p>
  </div>

  <div v-if="data" id="results-section" class="space-y-6 tool-results">
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="sm:col-span-2 glass rounded-3xl p-8 flex items-center gap-8">
        <div class="relative w-28 h-28 shrink-0">
          <svg class="w-28 h-28 -rotate-90" viewBox="0 0 120 120">
            <circle cx="60" cy="60" r="52" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="10" />
            <circle cx="60" cy="60" r="52" fill="none" stroke="url(#sg)" stroke-width="10" stroke-linecap="round" :stroke-dasharray="circumference()" :stroke-dashoffset="dashOffset(data.score)" style="transition:stroke-dashoffset 1s ease" />
            <defs>
              <linearGradient id="sg" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="#a3e635" />
                <stop offset="100%" stop-color="#0694a2" />
              </linearGradient>
            </defs>
          </svg>
          <div class="absolute inset-0 flex flex-col items-center justify-center">
            <span class="font-sans text-3xl font-700 leading-none" :style="{ color: scoreColor(data.score) }">{{ data.score }}</span>
            <span class="text-[10px] uppercase tracking-widest mt-1" style="color:var(--c-muted)">/100</span>
          </div>
        </div>
        <div>
          <p class="text-xs uppercase tracking-widest font-semibold mb-1" style="color:var(--c-muted)">SEO Score</p>
          <p class="font-sans text-2xl font-700 mb-1" :style="{ color: scoreColor(data.score) }">{{ scoreLabel(data.score) }}</p>
          <p class="text-xs mb-1" style="color:var(--c-muted)">{{ data.issues.length }} issue(s) - {{ data.passes.length }} passed</p>
          <span v-if="data.isHttps" class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full" style="background:rgba(101,163,13,0.12);color:#65a30d;border:1px solid rgba(101,163,13,0.25)">
            <i class="fa-solid fa-lock text-[10px]"></i> HTTPS
          </span>
          <span v-else class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full" style="background:rgba(248,113,113,0.12);color:#f87171;border:1px solid rgba(248,113,113,0.25)">
            <i class="fa-solid fa-unlock text-[10px]"></i> HTTP only
          </span>
          <br>
          <a :href="data.url" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 mt-2 text-xs font-semibold" style="color:#0694a2">
            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> {{ hostOf(data.url) }}
          </a>
        </div>
      </div>

      <div v-for="s in statCards(data)" :key="s.label" class="glass rounded-2xl p-5 text-center">
        <div class="w-9 h-9 rounded-xl mx-auto mb-3 flex items-center justify-center" :style="{ background: `${s.color}18`, border: `1px solid ${s.color}30` }">
          <i :class="s.icon" class="text-xs" :style="{ color: s.color }"></i>
        </div>
        <p class="font-sans text-2xl font-700 mb-1" :style="{ color: s.color }">{{ s.value }}</p>
        <p class="text-[10px] uppercase tracking-widest font-semibold" style="color:var(--c-muted)">{{ s.label }}</p>
      </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
      <div class="glass rounded-3xl overflow-hidden">
        <div class="px-6 py-4 flex items-center gap-3" style="border-bottom:1px solid var(--c-border);background:rgba(255,255,255,0.06)">
          <i class="fa-solid fa-triangle-exclamation text-xs" style="color:#f97316"></i>
          <span class="text-xs font-bold uppercase tracking-widest" style="color:var(--c-muted)">{{ data.issues.length }} Issues Found</span>
        </div>
        <div class="p-5 space-y-3">
          <div v-if="data.issues.length === 0" class="flex items-center gap-2 rounded-xl px-4 py-3" style="background:rgba(163,230,53,0.07);border:1px solid rgba(163,230,53,0.15)">
            <i class="fa-solid fa-thumbs-up text-xs" style="color:#65a30d"></i>
            <p class="text-sm" style="color:#65a30d">No issues found!</p>
          </div>
          <div v-for="(i, idx) in data.issues" :key="idx" class="rounded-lg px-3 py-2.5 space-y-1.5" style="background:rgba(249,115,22,0.07);border:1px solid rgba(249,115,22,0.15)">
            <div class="flex items-start gap-2">
              <i class="fa-solid fa-circle-xmark mt-0.5 shrink-0 text-xs" style="color:#f97316"></i>
              <div>
                <p class="text-xs font-semibold leading-snug" style="color:var(--c-text)">{{ i.label }}</p>
                <p class="text-[11px] mt-0.5 leading-snug" style="color:var(--c-muted)">{{ i.msg }}</p>
              </div>
            </div>
            <div v-if="i.fix" class="flex items-start gap-1.5 rounded px-2.5 py-1.5 ml-5" style="background:rgba(6,148,162,0.07);border:1px solid rgba(6,148,162,0.18)">
              <i class="fa-solid fa-wand-magic-sparkles mt-0.5 shrink-0 text-[10px]" style="color:#0694a2"></i>
              <span class="text-[11px] leading-snug" style="color:var(--c-muted)">
                <span class="font-semibold" style="color:#0694a2">Fix: </span>{{ i.fix }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="glass rounded-3xl overflow-hidden">
        <div class="px-6 py-4 flex items-center gap-3" style="border-bottom:1px solid var(--c-border);background:rgba(255,255,255,0.06)">
          <i class="fa-solid fa-circle-check text-xs" style="color:#65a30d"></i>
          <span class="text-xs font-bold uppercase tracking-widest" style="color:var(--c-muted)">{{ data.passes.length }} Checks Passed</span>
        </div>
        <div class="p-5 grid grid-cols-2 gap-2">
          <p v-if="data.passes.length === 0" class="text-xs col-span-2" style="color:var(--c-muted)">No checks passed.</p>
          <div v-for="(p, i) in data.passes" :key="i" class="flex items-start gap-2 rounded-lg px-3 py-2" style="background:rgba(163,230,53,0.07);border:1px solid rgba(163,230,53,0.15)">
            <i class="fa-solid fa-circle-check shrink-0 mt-0.5 text-xs" style="color:#65a30d"></i>
            <p class="text-xs leading-snug" style="color:var(--c-text)">{{ p }}</p>
          </div>
        </div>
      </div>
    </div>

    <div class="glass rounded-3xl overflow-hidden">
      <div class="px-6 py-4 flex items-center gap-3" style="border-bottom:1px solid var(--c-border);background:rgba(255,255,255,0.06)">
        <i class="fa-solid fa-tags text-xs" style="color:#0694a2"></i>
        <span class="text-xs font-bold uppercase tracking-widest" style="color:var(--c-muted)">Meta Details</span>
      </div>
      <div class="p-6 grid sm:grid-cols-2 gap-4">
        <div v-for="row in [
          { label: 'Title', value: data.title, extra: data.title ? `${data.title.length} chars` : '', status: titleStatus(data) },
          { label: 'Meta Description', value: data.metaDesc, extra: data.metaDesc ? `${data.metaDesc.length} chars` : '', status: descStatus(data) },
          { label: 'Canonical', value: data.canonical, extra: null, status: data.canonical ? 'ok' : 'err' },
          { label: 'Robots', value: data.metaRobots || 'index, follow (default)', extra: null, status: data.metaRobots && data.metaRobots.toLowerCase().includes('noindex') ? 'err' : 'ok' },
          { label: 'Viewport', value: data.viewport, extra: null, status: data.viewport ? 'ok' : 'err' },
          { label: 'Charset', value: data.charset || 'Not declared', extra: null, status: data.charset ? 'ok' : 'warn' },
          { label: 'HTML Lang', value: data.langAttr, extra: null, status: data.langAttr ? 'ok' : 'warn' },
          { label: 'Favicon', value: data.hasFavicon ? 'Detected' : 'Not found', extra: null, status: data.hasFavicon ? 'ok' : 'warn' },
        ]" :key="row.label" class="rounded-xl overflow-hidden" style="border:1px solid var(--c-border)">
          <div class="flex items-center justify-between px-4 py-2" style="background:rgba(255,255,255,0.05);border-bottom:1px solid var(--c-border)">
            <span class="text-[10px] font-black uppercase tracking-widest" style="color:var(--c-muted)">
              <span class="w-1.5 h-1.5 rounded-full inline-block mr-1" :style="{ background: dotColor(row.status) }"></span>
              {{ row.label }}
            </span>
            <span v-if="row.extra" class="text-[10px] font-semibold" style="color:#0694a2">{{ row.extra }}</span>
          </div>
          <p class="px-4 py-3 text-sm break-all" :style="{ color: row.value ? 'var(--c-text)' : 'var(--c-muted)' }">{{ row.value || 'Not set' }}</p>
        </div>
      </div>
    </div>

    <div class="glass rounded-3xl overflow-hidden">
      <div class="px-6 py-4 flex items-center gap-3" style="border-bottom:1px solid var(--c-border);background:rgba(255,255,255,0.06)">
        <i class="fa-solid fa-share-nodes text-xs" style="color:#16bdca"></i>
        <span class="text-xs font-bold uppercase tracking-widest" style="color:var(--c-muted)">Open Graph &amp; Twitter Cards</span>
      </div>
      <div class="p-6 grid sm:grid-cols-2 gap-4">
        <div v-for="row in [
          { label: 'OG Title', value: data.og['og:title'] || '', status: data.og['og:title'] ? 'ok' : 'err' },
          { label: 'OG Description', value: data.og['og:description'] || '', status: data.og['og:description'] ? 'ok' : 'err' },
          { label: 'OG Image', value: data.og['og:image'] || '', status: data.og['og:image'] ? 'ok' : 'err' },
          { label: 'OG URL', value: data.og['og:url'] || '', status: data.og['og:url'] ? 'ok' : 'warn' },
          { label: 'Twitter Card', value: data.twitter['twitter:card'] || '', status: data.twitter['twitter:card'] ? 'ok' : 'warn' },
          { label: 'Twitter Title', value: data.twitter['twitter:title'] || '', status: data.twitter['twitter:title'] ? 'ok' : 'warn' },
        ]" :key="row.label" class="rounded-xl overflow-hidden" style="border:1px solid var(--c-border)">
          <div class="flex items-center justify-between px-4 py-2" style="background:rgba(255,255,255,0.05);border-bottom:1px solid var(--c-border)">
            <span class="text-[10px] font-black uppercase tracking-widest" style="color:var(--c-muted)">
              <span class="w-1.5 h-1.5 rounded-full inline-block mr-1" :style="{ background: dotColor(row.status) }"></span>
              {{ row.label }}
            </span>
          </div>
          <p class="px-4 py-3 text-sm break-all" :style="{ color: row.value ? 'var(--c-text)' : 'var(--c-muted)' }">{{ row.value || 'Not set' }}</p>
        </div>
      </div>
    </div>

    <div class="glass rounded-3xl overflow-hidden">
      <div class="px-6 py-4 flex items-center gap-3" style="border-bottom:1px solid var(--c-border);background:rgba(255,255,255,0.06)">
        <i class="fa-solid fa-code text-xs" style="color:#a3e635"></i>
        <span class="text-xs font-bold uppercase tracking-widest" style="color:var(--c-muted)">Structured Data (Schema.org)</span>
      </div>
      <div class="p-6">
        <template v-if="data.hasSchema && data.schemaTypes.length">
          <p class="text-xs mb-3" style="color:var(--c-muted)">JSON-LD schema types detected on this page:</p>
          <div class="flex flex-wrap gap-2">
            <span v-for="(t, i) in data.schemaTypes" :key="i" class="text-xs font-semibold px-3 py-1 rounded-full inline-flex items-center gap-1" style="background:rgba(163,230,53,0.12);color:#65a30d;border:1px solid rgba(163,230,53,0.25)">
              <i class="fa-solid fa-check text-[10px]"></i>{{ t }}
            </span>
          </div>
        </template>
        <div v-else class="flex items-start gap-3 rounded-xl px-4 py-3" style="background:rgba(249,115,22,0.07);border:1px solid rgba(249,115,22,0.15)">
          <i class="fa-solid fa-triangle-exclamation mt-0.5 text-xs" style="color:#f97316"></i>
          <div>
            <p class="text-sm font-semibold" style="color:var(--c-text)">No structured data found</p>
            <p class="text-xs mt-1" style="color:var(--c-muted)">Add JSON-LD markup (Article, Product, LocalBusiness, FAQPage, etc.) to help search engines understand your content and unlock rich results.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="glass rounded-3xl overflow-hidden">
      <div class="px-6 py-4 flex items-center gap-3" style="border-bottom:1px solid var(--c-border);background:rgba(255,255,255,0.06)">
        <i class="fa-solid fa-heading text-xs" style="color:#16bdca"></i>
        <span class="text-xs font-bold uppercase tracking-widest" style="color:var(--c-muted)">
          Heading Structure - H1: {{ data.h1.length }} - H2: {{ data.h2.length }} - H3: {{ data.h3.length }}
        </span>
      </div>
      <div class="p-6 space-y-5">
        <div v-for="block in [
          { tag: 'H1', items: data.h1, color: '#0694a2' },
          { tag: 'H2', items: data.h2, color: '#16bdca' },
          { tag: 'H3', items: data.h3, color: '#a3e635' },
        ]" :key="block.tag">
          <template v-if="block.items.length">
            <p class="text-[10px] font-black uppercase tracking-widest mb-2" :style="{ color: block.color }">{{ block.tag }} ({{ block.items.length }})</p>
            <div class="space-y-1.5">
              <div v-for="(h, i) in block.items.slice(0, 8)" :key="i" class="rounded-xl px-4 py-2.5 text-sm" style="background:rgba(255,255,255,0.04);border:1px solid var(--c-border)">{{ h }}</div>
              <p v-if="block.items.length > 8" class="text-xs px-2 mt-1" style="color:var(--c-muted)">+{{ block.items.length - 8 }} more...</p>
            </div>
          </template>
        </div>
        <p v-if="!data.h1.length && !data.h2.length && !data.h3.length" class="text-sm" style="color:var(--c-muted)">No headings found on this page.</p>
      </div>
    </div>

    <div v-if="data.missingAlt > 0 && data.missingAltImages.length > 0" class="glass rounded-3xl overflow-hidden">
      <div class="px-6 py-4 flex items-center gap-3" style="border-bottom:1px solid var(--c-border);background:rgba(255,255,255,0.06)">
        <i class="fa-solid fa-image text-xs" style="color:#f87171"></i>
        <span class="text-xs font-bold uppercase tracking-widest" style="color:var(--c-muted)">Images Missing ALT Text ({{ data.missingAlt }})</span>
      </div>
      <div class="p-6 space-y-2">
        <div v-for="(src, i) in data.missingAltImages" :key="i" class="flex items-center gap-3 rounded-xl px-4 py-2.5" style="background:rgba(248,113,113,0.06);border:1px solid rgba(248,113,113,0.15)">
          <i class="fa-solid fa-circle-exclamation shrink-0 text-xs" style="color:#f87171"></i>
          <span class="text-xs font-mono truncate" style="color:var(--c-muted)">{{ src }}</span>
        </div>
        <p v-if="data.missingAlt > data.missingAltImages.length" class="text-xs px-2 mt-1" style="color:var(--c-muted)">+{{ data.missingAlt - data.missingAltImages.length }} more...</p>
      </div>
    </div>
  </div>
</template>
