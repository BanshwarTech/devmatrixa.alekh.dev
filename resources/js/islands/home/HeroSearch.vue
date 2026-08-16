<script setup>
import { toolState } from './toolStore';

function onSubmit() {
  document.getElementById('tools')?.scrollIntoView({ behavior: 'smooth' });
}
</script>

<template>
  <div class="max-w-xl mb-8 relative">
    <div class="flex items-center gap-3 rounded-2xl px-4 py-3.5" style="background:var(--c-surface);box-shadow:0 8px 32px rgba(6,148,162,0.16)">
      <i class="fa-solid fa-magnifying-glass text-sm shrink-0" style="color:#0694a2"></i>
      <input
        v-model="toolState.query"
        type="text"
        placeholder="Search anything..."
        autocomplete="off"
        @keydown.enter="onSubmit"
        class="w-full bg-transparent text-sm sm:text-base outline-none"
        style="color:var(--c-text);font-weight:500"
      >
      <button v-if="toolState.query" @click="toolState.query = ''" class="shrink-0 w-7 h-7 rounded-xl flex items-center justify-center text-sm font-bold transition-all" style="color:#0694a2;background:rgba(6,148,162,0.12)">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>
  </div>
</template>
