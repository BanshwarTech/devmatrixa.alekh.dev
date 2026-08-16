<script setup>
import { ref } from 'vue';
import { convertCssToTailwind } from './cssToTailwindLogic.js';

const css = ref('padding: 16px;\ndisplay: flex;\njustify-content: center;\nfont-weight: 700;\nborder-radius: 0.5rem;\nbackground-color: #0694a2;');
const results = ref(null);
const output = ref('');
const matched = ref(0);
const unmatched = ref(0);
const copied = ref(false);

function convert() {
  if (!css.value.trim()) return;
  const r = convertCssToTailwind(css.value);
  results.value = r.results;
  output.value = r.tailwindOutput;
  matched.value = r.matched;
  unmatched.value = r.unmatched;
}

function clear() {
  css.value = '';
  results.value = null;
}

async function copy() {
  try {
    await navigator.clipboard.writeText(output.value);
    copied.value = true;
    setTimeout(() => {
      copied.value = false;
    }, 1500);
  } catch {
    // ignore
  }
}
</script>

<template>
  <div class="grid lg:grid-cols-2 gap-6">
    <div class="glass rounded-3xl p-6">
      <div class="flex items-center justify-between mb-3">
        <p class="text-xs font-bold uppercase tracking-widest" style="color:#0694a2">Input CSS</p>
        <button @click="clear" class="btn-outline text-xs px-3 py-1 rounded-full">Clear</button>
      </div>
      <textarea
        v-model="css"
        rows="14"
        class="field w-full p-4 rounded-2xl text-sm font-mono"
        placeholder="Paste CSS here..."
      ></textarea>
      <button @click="convert" class="btn-primary w-full mt-4 px-6 py-3 rounded-2xl text-sm font-700 inline-flex items-center justify-center gap-2">
        <i class="fa-solid fa-wand-magic-sparkles text-xs" style="color:#061c21"></i> Convert
      </button>
    </div>

    <div class="glass rounded-3xl p-6">
      <div class="flex items-center justify-between mb-3">
        <p class="text-xs font-bold uppercase tracking-widest" style="color:#0694a2">Tailwind Output</p>
        <button v-if="output" @click="copy" class="btn-outline text-xs px-3 py-1 rounded-full inline-flex items-center gap-1.5">
          <i :class="copied ? 'fa-solid fa-check' : 'fa-solid fa-copy'" class="text-[10px]"></i>{{ copied ? 'Copied!' : 'Copy' }}
        </button>
      </div>
      <pre class="p-4 rounded-2xl text-sm font-mono min-h-[340px] whitespace-pre-wrap break-words" style="background:#0a2f36;color:#a3e635">{{ output || '// Click Convert' }}</pre>
      <div v-if="results" class="flex items-center gap-3 mt-3 text-xs">
        <span class="flex items-center gap-1.5" style="color:#65a30d"><i class="fa-solid fa-check text-xs"></i> {{ matched }} matched</span>
        <span v-if="unmatched > 0" class="flex items-center gap-1.5" style="color:#f87171"><i class="fa-solid fa-xmark text-xs"></i> {{ unmatched }} unmatched</span>
      </div>
    </div>
  </div>

  <div v-if="results && results.length > 0" class="glass rounded-3xl mt-6 overflow-hidden">
    <div class="px-5 py-3" style="border-bottom:1px solid var(--c-border);background:rgba(255,255,255,0.06)">
      <p class="text-xs font-bold uppercase tracking-widest" style="color:var(--c-muted)">Per-Declaration Mapping</p>
    </div>
    <div class="divide-y" style="border-color:var(--c-border)">
      <div v-for="(r, i) in results" :key="i" class="grid grid-cols-3 gap-3 px-5 py-3 text-xs">
        <div class="font-mono truncate" style="color:var(--c-text)">{{ r.property }}: <span style="color:var(--c-muted)">{{ r.value }}</span></div>
        <div class="font-mono" :style="{ color: r.matched ? '#a3e635' : '#f87171' }">{{ r.tailwind ?? '—' }}</div>
        <div class="text-right">
          <span v-if="r.matched" class="text-[10px] px-2 py-0.5 rounded-full" style="background:rgba(163,230,53,0.12);color:#65a30d">matched</span>
          <span v-else class="text-[10px] px-2 py-0.5 rounded-full" style="background:rgba(248,113,113,0.12);color:#f87171">no match</span>
        </div>
      </div>
    </div>
  </div>
</template>
