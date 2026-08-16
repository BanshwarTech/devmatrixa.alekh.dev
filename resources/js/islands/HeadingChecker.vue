<script setup>
import { ref } from 'vue';
import StatTile from './shared/StatTile.vue';

const url = ref('');
const focused = ref(false);
const data = ref(null);
const loading = ref(false);
const error = ref('');

const issueColor = (t) => (t === 'error' ? '#f87171' : t === 'warning' ? '#f97316' : '#0694a2');
const issueIcon = (t) => (t === 'error' ? 'fa-solid fa-circle-xmark' : t === 'warning' ? 'fa-solid fa-triangle-exclamation' : 'fa-solid fa-circle-info');

async function analyze() {
  const trimmed = url.value.trim();
  if (!trimmed) { error.value = 'Please enter a URL.'; return; }
  if (!/^https?:\/\/.+/.test(trimmed)) { error.value = 'URL must start with http:// or https://'; return; }
  error.value = '';
  data.value = null;
  loading.value = true;
  try {
    const res = await fetch('/api/heading-checker', {
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
          <h2 class="font-sans text-2xl sm:text-3xl font-700 tracking-tight leading-tight">Enter a URL to audit headings.</h2>
          <p class="mt-2 text-sm sm:text-[15px] leading-relaxed max-w-xl" style="color:var(--c-muted)">Fast and free.</p>
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
            <i class="fa-solid fa-heading text-xs"></i>
            Check Headings
          </template>
        </button>
      </div>

      <div v-if="error" class="mt-5 rounded-2xl px-4 py-3 text-sm font-semibold flex items-start gap-3" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);color:#ef4444">
        <i class="fa-solid fa-triangle-exclamation mt-0.5 shrink-0"></i>
        <span class="leading-snug">{{ error }}</span>
      </div>
    </div>
  </div>

  <div v-if="data" class="space-y-6 tool-results">
    <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
      <StatTile v-for="l in [1, 2, 3, 4, 5, 6]" :key="l" :value="data.counts['h' + l]" :label="`H${l}`" color="#0694a2" />
    </div>

    <div class="glass rounded-3xl overflow-hidden">
      <div class="px-6 py-4 flex items-center gap-3" style="border-bottom:1px solid var(--c-border);background:rgba(255,255,255,0.06)">
        <i class="fa-solid fa-triangle-exclamation text-xs" style="color:#f97316"></i>
        <span class="text-xs font-bold uppercase tracking-widest" style="color:var(--c-muted)">{{ data.issues.length }} Issues</span>
      </div>
      <div class="p-5 space-y-3">
        <div v-if="data.issues.length === 0" class="flex items-center gap-2 rounded-xl px-4 py-3" style="background:rgba(163,230,53,0.07);border:1px solid rgba(163,230,53,0.15)">
          <i class="fa-solid fa-circle-check text-xs" style="color:#65a30d"></i>
          <p class="text-sm" style="color:#65a30d">Heading structure looks great!</p>
        </div>
        <div v-for="(i, idx) in data.issues" :key="idx" class="rounded-lg px-3 py-2.5 space-y-1.5" :style="{ background: `${issueColor(i.type)}10`, border: `1px solid ${issueColor(i.type)}30` }">
          <div class="flex items-start gap-2">
            <i :class="issueIcon(i.type)" class="mt-0.5 shrink-0 text-xs" :style="{ color: issueColor(i.type) }"></i>
            <div>
              <p class="text-xs font-semibold leading-snug" style="color:var(--c-text)">{{ i.msg }}</p>
              <p class="text-[11px] mt-0.5 leading-snug" style="color:var(--c-muted)"><span class="font-semibold" style="color:#0694a2">Fix: </span>{{ i.fix }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="glass rounded-3xl overflow-hidden">
      <div class="px-6 py-4 flex items-center gap-3" style="border-bottom:1px solid var(--c-border);background:rgba(255,255,255,0.06)">
        <i class="fa-solid fa-diagram-project text-xs" style="color:#16bdca"></i>
        <span class="text-xs font-bold uppercase tracking-widest" style="color:var(--c-muted)">Heading Tree ({{ data.total }} total)</span>
      </div>
      <div class="p-5 space-y-1.5">
        <div v-for="(h, i) in data.headings" :key="i" class="rounded-lg px-3 py-2 flex items-center gap-3 text-sm" :style="{ background: 'rgba(255,255,255,0.04)', border: '1px solid var(--c-border)', marginLeft: `${(h.level - 1) * 20}px` }">
          <span class="text-[10px] font-black px-2 py-0.5 rounded" style="background:rgba(6,148,162,0.15);color:#0694a2">H{{ h.level }}</span>
          <span :class="h.text === '(empty)' ? 'italic' : ''" :style="{ color: h.text === '(empty)' ? 'var(--c-muted)' : 'var(--c-text)' }">{{ h.text }}</span>
        </div>
      </div>
    </div>

    <div v-if="data.hasChanges" class="glass rounded-3xl overflow-hidden">
      <div class="px-6 py-4 flex items-center gap-3" style="border-bottom:1px solid var(--c-border);background:rgba(255,255,255,0.06)">
        <i class="fa-solid fa-wand-magic-sparkles text-xs" style="color:#a3e635"></i>
        <span class="text-xs font-bold uppercase tracking-widest" style="color:var(--c-muted)">Suggested Fix</span>
      </div>
      <div class="p-5 space-y-1.5">
        <div v-for="(s, i) in data.suggested" :key="i" class="rounded-lg px-3 py-2 flex items-center gap-3 text-sm" :style="{ background: s.changed ? 'rgba(163,230,53,0.07)' : 'rgba(255,255,255,0.04)', border: `1px solid ${s.changed ? 'rgba(163,230,53,0.25)' : 'var(--c-border)'}`, marginLeft: `${(s.suggestedLevel - 1) * 20}px` }">
          <span v-if="s.changed" class="text-[10px] font-bold px-2 py-0.5 rounded line-through opacity-60" style="background:rgba(248,113,113,0.15);color:#f87171">H{{ s.level }}</span>
          <span class="text-[10px] font-black px-2 py-0.5 rounded" style="background:rgba(163,230,53,0.15);color:#65a30d">H{{ s.suggestedLevel }}</span>
          <span style="color:var(--c-text)">{{ s.text }}</span>
        </div>
      </div>
    </div>
  </div>
</template>
