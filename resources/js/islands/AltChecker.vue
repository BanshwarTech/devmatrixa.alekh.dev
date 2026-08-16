<script setup>
import { ref } from 'vue';

const urls = ref('');
const data = ref(null);
const loading = ref(false);
const error = ref('');

async function analyze() {
  if (!urls.value.trim()) {
    error.value = 'Paste one or more URLs.';
    return;
  }
  error.value = '';
  data.value = null;
  loading.value = true;
  try {
    const res = await fetch('/api/alt-checker', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ urls: urls.value }),
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
  <div class="relative glass rounded-3xl p-8 md:p-12 mb-10 overflow-hidden">
    <div class="relative z-10">
      <div class="flex items-center justify-between gap-4 mb-6">
        <div>
          <p class="text-xs uppercase tracking-widest font-semibold mb-1" style="color:#0694a2">Analyze Image Alt Text</p>
          <h2 class="font-sans text-2xl sm:text-3xl font-700 tracking-tight">Enter your website URL for alt tag analysis.</h2>
        </div>
        <span class="text-[10px] uppercase tracking-widest font-semibold px-2.5 py-1 rounded-full" style="background:rgba(166,230,53,0.12);color:#a3e635;border:1px solid rgba(166,230,53,0.25)">Instant results</span>
      </div>
      <textarea
        v-model="urls"
        rows="6"
        placeholder="Enter Website URL"
        class="field w-full p-4 rounded-2xl text-sm font-mono"
      ></textarea>
      <button @click="analyze" :disabled="loading" class="btn-primary w-full mt-4 px-6 py-3 rounded-2xl text-sm font-700 inline-flex items-center justify-center gap-2 disabled:opacity-70">
        <i class="fa-solid fa-magnifying-glass text-xs" style="color:#061c21"></i>
        {{ loading ? 'Analyzing...' : 'Start Alt Analysis' }}
      </button>
      <div v-if="error" class="mt-4 rounded-2xl p-4 text-sm font-semibold" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);color:#f87171">
        {{ error }}
      </div>
    </div>
  </div>

  <div v-if="data" class="space-y-4">
    <div v-for="(r, url) in data.results" :key="url" class="glass rounded-3xl overflow-hidden">
      <div class="px-5 py-3 flex items-center justify-between" style="border-bottom:1px solid var(--c-border);background:rgba(255,255,255,0.06)">
        <a :href="url" target="_blank" rel="noopener noreferrer" class="text-xs font-semibold truncate" style="color:#0694a2">{{ url }}</a>
        <span v-if="r.error" class="text-xs font-bold" style="color:#f87171">{{ r.error }}</span>
        <span v-else class="text-xs font-bold" :style="{ color: r.issues.length > 0 ? '#f97316' : '#65a30d' }">
          {{ r.issues.length }} issues / {{ r.total }} images
        </span>
      </div>
      <div v-if="r.issues && r.issues.length > 0" class="p-4 space-y-2 max-h-80 overflow-y-auto">
        <div v-for="(iss, i) in r.issues" :key="i" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs" style="background:rgba(248,113,113,0.06);border:1px solid rgba(248,113,113,0.15)">
          <i class="fa-solid fa-circle-exclamation" style="color:#f87171"></i>
          <span class="font-mono truncate flex-1" style="color:var(--c-muted)">{{ iss.src }}</span>
        </div>
      </div>
    </div>
  </div>
</template>
