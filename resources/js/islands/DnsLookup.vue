<script setup>
import LucideIcon from '../components/LucideIcon.js';
import { ref, computed } from 'vue';
import StatTile from './shared/StatTile.vue';

const domain = ref('');
const focused = ref(false);
const data = ref(null);
const loading = ref(false);
const error = ref('');
const copiedKey = ref(null);
const onlyFound = ref(false);

const examples = ['example.com', 'google.com', 'github.com', 'cloudflare.com'];

const typeColor = {
  A: '#0098f0', AAAA: '#0078e0', CNAME: '#16bdca', MX: '#0694a2', TXT: '#f59e0b',
  NS: '#7edce2', SOA: '#16bdca', CAA: '#f97316', DMARC: '#f59e0b', PTR: '#0098f0',
};
const tagColor = { SPF: '#0694a2', DKIM: '#16bdca', DMARC: '#f59e0b', Verification: '#0098f0' };

const visibleRecords = computed(() => {
  if (!data.value) return [];
  return data.value.records.filter((r) => (onlyFound.value ? r.status === 'found' : true));
});

function useExample(ex) {
  domain.value = ex;
}

async function analyze() {
  const trimmed = domain.value.trim();
  if (!trimmed) {
    error.value = 'Please enter a domain.';
    return;
  }
  error.value = '';
  data.value = null;
  loading.value = true;
  try {
    const res = await fetch('/api/dns-lookup', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ domain: trimmed }),
    });
    const j = await res.json();
    if (!res.ok || j.error) error.value = j.error || 'Lookup failed.';
    else data.value = j;
  } catch {
    error.value = 'Network error.';
  }
  loading.value = false;
}

async function copyVal(key, text) {
  try {
    await navigator.clipboard.writeText(text);
    copiedKey.value = key;
    setTimeout(() => {
      if (copiedKey.value === key) copiedKey.value = null;
    }, 1500);
  } catch {
    // ignore
  }
}
</script>

<template>
  <div class="url-form relative glass rounded-[28px] p-6 sm:p-9 md:p-11 mb-10 overflow-hidden">
    <div class="absolute top-0 left-8 right-8 h-[2px] rounded-b-full" style="background:linear-gradient(90deg, transparent, #0694a2, #0098f0, #16bdca, transparent)"></div>
    <div class="url-form-mesh"></div>

    <div class="relative z-10">
      <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-7">
        <div class="min-w-0">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest mb-3" style="background:rgba(6,148,162,0.08);border:1px solid rgba(6,148,162,0.20);color:#0694a2">
            <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:#0694a2"></span>
            Devmatrixa Tool
          </div>
          <h2 class="font-sans text-2xl sm:text-3xl font-700 tracking-tight leading-tight">Enter a domain to resolve.</h2>
        </div>
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold shrink-0 self-start" style="background:rgba(0, 152, 240,0.10);border:1px solid rgba(0, 152, 240,0.28);color:#4010b0">
          <LucideIcon class="text-xs" name="bolt"></LucideIcon>
          Fast & free
        </div>
      </div>

      <div :class="['url-input-wrap', focused ? 'focused' : '']" class="flex gap-2 sm:gap-3 flex-col sm:flex-row p-1.5 rounded-2xl">
        <div class="relative flex-1">
          <LucideIcon class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none text-sm" :style="{ color: focused ? '#0694a2' : 'var(--c-muted)' }" name="globe"></LucideIcon>
          <input
            v-model="domain"
            type="text"
            @focus="focused = true"
            @blur="focused = false"
            @keydown.enter="!loading && analyze()"
            placeholder="example.com"
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
            <LucideIcon class="text-xs" name="server"></LucideIcon>
            Look Up
          </template>
        </button>
      </div>

      <div class="mt-5 flex flex-wrap items-center gap-2">
        <span class="text-[10px] font-bold uppercase tracking-widest mr-1 inline-flex items-center gap-1.5" style="color:var(--c-muted)">
          <LucideIcon class="text-xs" style="color:#0694a2" name="wand-sparkles"></LucideIcon>
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
          <LucideIcon class="text-[11px]" style="color:#0694a2" name="arrow-up-right-from-square"></LucideIcon>
          {{ ex }}
        </button>
      </div>

      <div v-if="error" class="mt-5 rounded-2xl px-4 py-3 text-sm font-semibold flex items-start gap-3" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);color:#ef4444">
        <LucideIcon class="mt-0.5 shrink-0" name="triangle-alert"></LucideIcon>
        <span class="leading-snug">{{ error }}</span>
      </div>
    </div>
  </div>

  <div v-if="data" class="space-y-4">
    <div class="glass rounded-3xl p-5 sm:p-6 flex flex-col sm:flex-row items-center gap-5">
      <div class="flex items-center justify-center w-28 h-28 sm:w-32 sm:h-32 rounded-2xl shrink-0" style="background:linear-gradient(135deg, #16bdca22, #0098f011);border:2px solid #16bdca;box-shadow:0 0 32px #16bdca33">
        <LucideIcon class="text-5xl" style="color:#16bdca" name="server"></LucideIcon>
      </div>
      <div class="flex-1 text-center sm:text-left">
        <div class="text-[11px] uppercase tracking-widest font-semibold mb-1" style="color:var(--c-muted)">Resolved Domain</div>
        <div class="font-sans text-2xl sm:text-3xl font-700 tracking-tight mb-2 break-all">{{ data.domain }}</div>
        <div class="text-xs" style="color:var(--c-muted)">
          <span class="font-mono">{{ data.counts.total }}</span> records across
          <span class="font-mono">{{ data.counts.found }}</span> record types
        </div>
      </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
      <StatTile :value="data.counts.total" label="Total Records" color="#0098f0" />
      <StatTile :value="data.counts.found" label="Record Types" color="#16bdca" />
      <StatTile :value="data.counts.types - data.counts.found" label="Empty" color="#7edce2" />
      <StatTile :value="data.counts.errors" label="Errors" color="#f87171" clickable :active="onlyFound" @click="onlyFound = !onlyFound" />
    </div>

    <div class="flex items-center justify-end">
      <button
        type="button"
        @click="onlyFound = !onlyFound"
        class="text-[11px] font-semibold px-3 py-1.5 rounded-full inline-flex items-center gap-1.5 transition-colors"
        :style="{
          background: onlyFound ? '#16bdca22' : 'rgba(255,255,255,0.04)',
          color: onlyFound ? '#16bdca' : 'var(--c-muted)',
          border: `1px solid ${onlyFound ? '#16bdca55' : 'var(--c-border)'}`,
        }"
      >
        <LucideIcon class="text-[11px]" :name="onlyFound ? 'check' : 'layers'"></LucideIcon>
        {{ onlyFound ? 'Showing records with data' : 'Hide empty record types' }}
      </button>
    </div>

    <div class="space-y-2.5">
      <div
        v-for="r in visibleRecords"
        :key="r.type"
        class="glass rounded-2xl p-4 sm:p-5"
        :style="{ borderLeft: `3px solid ${r.status === 'error' ? '#f87171' : r.status === 'none' ? 'var(--c-border)' : (typeColor[r.type] ?? '#16bdca')}` }"
      >
        <div class="flex flex-wrap items-center gap-2 mb-2">
          <span class="font-mono text-[12px] font-700 px-2 py-0.5 rounded shrink-0" :style="{ background: `${typeColor[r.type] ?? '#16bdca'}22`, color: typeColor[r.type] ?? '#16bdca' }">{{ r.type }}</span>
          <span class="font-sans text-[13px] font-700">{{ r.label.replace(/^[A-Z]+ — /, '') }}</span>
          <span v-if="r.status === 'none'" class="text-[10px] font-bold px-1.5 py-0.5 rounded ml-auto" style="background:rgba(255,255,255,0.06);color:var(--c-muted)">NO RECORDS</span>
          <span v-if="r.status === 'error'" class="text-[10px] font-bold px-1.5 py-0.5 rounded ml-auto inline-flex items-center gap-1" style="background:#f8717122;color:#f87171">
            <LucideIcon class="text-[10px]" name="triangle-alert"></LucideIcon> {{ r.error }}
          </span>
        </div>
        <p class="text-[12.5px] leading-relaxed mb-2" style="color:var(--c-muted)">{{ r.desc }}</p>

        <div v-if="r.values.length > 0" class="space-y-1.5">
          <div v-for="(v, i) in r.values" :key="`${r.type}-${i}`" class="group flex items-center gap-2 rounded-lg px-2.5 py-2" style="background:rgba(255,255,255,0.04)">
            <span v-if="v.tag" class="text-[9px] font-bold px-1.5 py-0.5 rounded shrink-0" :style="{ background: `${tagColor[v.tag] ?? typeColor[r.type]}22`, color: tagColor[v.tag] ?? typeColor[r.type] }">{{ v.tag }}</span>
            <span class="font-mono text-[12px] break-all flex-1 min-w-0">{{ v.value }}</span>
            <span v-if="v.meta" class="font-mono text-[10px] shrink-0 hidden sm:inline" style="color:var(--c-muted)">{{ v.meta }}</span>
            <button
              type="button"
              @click="copyVal(`${r.type}-${i}`, v.value)"
              aria-label="Copy value"
              class="shrink-0 w-6 h-6 rounded-md flex items-center justify-center transition-colors"
              :style="{ background: copiedKey === `${r.type}-${i}` ? '#4010b022' : 'rgba(255,255,255,0.06)', color: copiedKey === `${r.type}-${i}` ? '#4010b0' : 'var(--c-muted)' }"
            >
              <LucideIcon class="text-[11px]" :name="copiedKey === `${r.type}-${i}` ? 'check' : 'copy'"></LucideIcon>
            </button>
          </div>
        </div>
      </div>

      <div v-if="visibleRecords.length === 0" class="glass rounded-2xl p-6 text-center text-sm" style="color:var(--c-muted)">
        No records to show. Toggle the filter to see empty record types.
      </div>
    </div>

    <div class="flex items-center gap-2 text-[11px] justify-center pt-1" style="color:var(--c-muted)">
      <LucideIcon style="color:#4010b0" name="circle-check"></LucideIcon>
      Live results — nothing is stored. Re-run anytime to see propagation changes.
    </div>
  </div>
</template>
