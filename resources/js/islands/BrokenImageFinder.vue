<script setup>
import { ref, computed } from 'vue';
import StatTile from './shared/StatTile.vue';

const url = ref('');
const focused = ref(false);
const data = ref(null);
const loading = ref(false);
const error = ref('');
const filter = ref('all');

const filters = ['all', 'broken', 'working'];

const filtered = computed(() => {
  if (!data.value) return [];
  return data.value.images.filter((i) => filter.value === 'all' || (filter.value === 'broken' ? i.broken : !i.broken));
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
  loading.value = true;
  try {
    const res = await fetch('/api/broken-image-finder', {
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
          <h2 class="font-sans text-2xl sm:text-3xl font-700 tracking-tight leading-tight">Enter a URL to scan images.</h2>
          <p class="mt-2 text-sm sm:text-[15px] leading-relaxed max-w-xl" style="color:var(--c-muted)">Fast and free.</p>
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
            <i class="fa-solid fa-image text-xs"></i>
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
    <div class="grid grid-cols-3 gap-3">
      <StatTile :value="data.total" label="Total" color="#a3e635" />
      <StatTile :value="data.broken" label="Broken" color="#f87171" />
      <StatTile :value="data.working" label="Working" color="#65a30d" />
    </div>

    <div class="flex gap-2">
      <button
        v-for="f in filters"
        :key="f"
        type="button"
        @click="filter = f"
        :class="['cpill', 'px-4', 'py-2', 'rounded-full', 'text-xs', filter === f ? 'active' : '']"
      >{{ f }}</button>
    </div>

    <div class="glass rounded-3xl p-4 space-y-2 max-h-[600px] overflow-y-auto">
      <div
        v-for="(img, i) in filtered"
        :key="i"
        class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs"
        :style="{ background: img.broken ? 'rgba(248,113,113,0.06)' : 'rgba(163,230,53,0.06)', border: `1px solid ${img.broken ? 'rgba(248,113,113,0.15)' : 'rgba(163,230,53,0.15)'}` }"
      >
        <i :class="img.broken ? 'fa-solid fa-circle-xmark' : 'fa-solid fa-image'" class="shrink-0" :style="{ color: img.broken ? '#f87171' : '#65a30d' }"></i>
        <a :href="img.url" target="_blank" rel="noopener noreferrer" class="font-mono truncate flex-1" style="color:var(--c-muted)">{{ img.url }}</a>
        <span class="text-[10px] font-bold px-2 py-0.5 rounded shrink-0" :style="{ background: img.broken ? 'rgba(248,113,113,0.15)' : 'rgba(163,230,53,0.15)', color: img.broken ? '#f87171' : '#65a30d' }">{{ img.status || '—' }}</span>
      </div>
    </div>
  </div>
</template>
