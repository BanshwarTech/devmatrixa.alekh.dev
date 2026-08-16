<script setup>
import { ref, computed } from 'vue';
import StatTile from './shared/StatTile.vue';

const url = ref('');
const focused = ref(false);
const data = ref(null);
const loading = ref(false);
const error = ref('');
const copiedKey = ref(null);
const filter = ref('all');

const statusMeta = {
  good: { color: '#65a30d', bg: '#65a30d22', label: 'GOOD', icon: 'fa-solid fa-circle-check' },
  weak: { color: '#f59e0b', bg: '#f59e0b22', label: 'WEAK', icon: 'fa-solid fa-triangle-exclamation' },
  missing: { color: '#f87171', bg: '#f8717122', label: 'MISSING', icon: 'fa-solid fa-circle-xmark' },
  leak: { color: '#f97316', bg: '#f9731622', label: 'LEAK', icon: 'fa-solid fa-file-circle-exclamation' },
};

const visible = computed(() => {
  if (!data.value) return [];
  return data.value.checks.filter((c) => (filter.value === 'all' ? true : c.status === filter.value));
});

async function analyze() {
  const trimmed = url.value.trim();
  if (!trimmed) { error.value = 'Please enter a URL.'; return; }
  if (!/^https?:\/\/.+/.test(trimmed)) { error.value = 'URL must start with http:// or https://'; return; }
  error.value = '';
  data.value = null;
  loading.value = true;
  try {
    const res = await fetch('/api/security-headers', {
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

async function copyFix(key, fix) {
  try {
    await navigator.clipboard.writeText(fix);
    copiedKey.value = key;
    setTimeout(() => { if (copiedKey.value === key) copiedKey.value = null; }, 1500);
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
          <h2 class="font-sans text-2xl sm:text-3xl font-700 tracking-tight leading-tight">Enter a URL to audit.</h2>
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
            <i class="fa-solid fa-shield-halved text-xs"></i>
            Audit
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
      <div class="relative flex items-center justify-center w-28 h-28 sm:w-32 sm:h-32 rounded-2xl shrink-0" :style="{ background: `linear-gradient(135deg, ${data.gradeColor}22, ${data.gradeColor}11)`, border: `2px solid ${data.gradeColor}`, boxShadow: `0 0 32px ${data.gradeColor}33` }">
        <span class="font-sans font-700 text-5xl sm:text-6xl tracking-tighter" :style="{ color: data.gradeColor }">{{ data.grade }}</span>
      </div>
      <div class="flex-1 text-center sm:text-left">
        <div class="text-[11px] uppercase tracking-widest font-semibold mb-1" style="color:var(--c-muted)">Overall Grade</div>
        <div class="font-sans text-2xl sm:text-3xl font-700 tracking-tight mb-2">
          {{ data.score }}<span class="text-base font-500" style="color:var(--c-muted)">/100</span>
        </div>
        <div class="text-xs break-all" style="color:var(--c-muted)">
          Audited: <span class="font-mono" style="color:#e5e7eb">{{ data.finalUrl }}</span> · HTTP {{ data.status }}
        </div>
      </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
      <StatTile :value="data.counts.good" label="Good" color="#65a30d" clickable :active="filter === 'good'" @click="filter = filter === 'good' ? 'all' : 'good'" />
      <StatTile :value="data.counts.weak" label="Weak" color="#f59e0b" clickable :active="filter === 'weak'" @click="filter = filter === 'weak' ? 'all' : 'weak'" />
      <StatTile :value="data.counts.missing" label="Missing" color="#f87171" clickable :active="filter === 'missing'" @click="filter = filter === 'missing' ? 'all' : 'missing'" />
      <StatTile :value="data.counts.leak" label="Info Leaks" color="#f97316" clickable :active="filter === 'leak'" @click="filter = filter === 'leak' ? 'all' : 'leak'" />
    </div>

    <div class="space-y-2.5">
      <div v-for="c in visible" :key="c.key" class="glass rounded-2xl p-4 sm:p-5" :style="{ borderLeft: `3px solid ${statusMeta[c.status].color}` }">
        <div class="flex flex-wrap items-start gap-3">
          <div class="flex items-center justify-center w-9 h-9 rounded-xl shrink-0" :style="{ background: statusMeta[c.status].bg, color: statusMeta[c.status].color }">
            <i :class="statusMeta[c.status].icon"></i>
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2 mb-1">
              <span class="font-mono text-[13px] font-700 break-all" style="color:#e5e7eb">{{ c.label }}</span>
              <span class="text-[10px] font-bold px-1.5 py-0.5 rounded shrink-0" :style="{ background: statusMeta[c.status].bg, color: statusMeta[c.status].color }">{{ statusMeta[c.status].label }}</span>
              <a :href="c.docs" target="_blank" rel="noopener noreferrer" class="text-[10px] inline-flex items-center gap-1 ml-auto" style="color:var(--c-muted)" title="MDN docs">
                docs <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
              </a>
            </div>
            <div v-if="c.value" class="font-mono text-[11px] rounded-lg px-2.5 py-1.5 mb-2 break-all" style="background:rgba(255,255,255,0.04);color:var(--c-muted)">
              {{ c.value }}
            </div>
            <p class="text-[13px] leading-relaxed mb-2" style="color:#e5e7eb">{{ c.why }}</p>
            <div v-if="c.status !== 'good'" class="rounded-lg p-2.5" style="background:rgba(6,148,162,0.08);border:1px solid rgba(6,148,162,0.25)">
              <div class="flex items-center justify-between gap-2 mb-1">
                <span class="text-[10px] uppercase tracking-widest font-semibold" style="color:#16bdca">How to fix</span>
                <button
                  type="button"
                  @click="copyFix(c.key, c.fix)"
                  class="flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded"
                  :style="{ background: copiedKey === c.key ? '#65a30d22' : 'rgba(255,255,255,0.06)', color: copiedKey === c.key ? '#65a30d' : 'var(--c-muted)' }"
                >
                  <i :class="copiedKey === c.key ? 'fa-solid fa-check' : 'fa-solid fa-copy'" class="text-[10px]"></i>
                  {{ copiedKey === c.key ? 'Copied' : 'Copy' }}
                </button>
              </div>
              <p class="font-mono text-[11.5px] leading-relaxed" style="color:#e5e7eb">{{ c.fix }}</p>
            </div>
          </div>
        </div>
      </div>
      <div v-if="visible.length === 0" class="glass rounded-2xl p-6 text-center text-sm" style="color:var(--c-muted)">
        No headers match this filter. Click a stat tile again to clear.
      </div>
    </div>
  </div>
</template>
