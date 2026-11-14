<script setup>
import LucideIcon from '../../components/LucideIcon.js';
import { toolState } from './toolStore';

function onSubmit() {
  document.getElementById('tools')?.scrollIntoView({ behavior: 'smooth' });
}
</script>

<template>
  <div class="max-w-xl mb-8 relative">
    <div class="site-search-field flex items-center gap-3 rounded-2xl px-4 py-3.5">
      <LucideIcon class="site-search-icon text-sm shrink-0" aria-hidden="true" name="search"></LucideIcon>
      <input
        v-model="toolState.query"
        type="text"
        placeholder="Search anything..."
        aria-label="Search tools"
        autocomplete="off"
        @keydown.enter="onSubmit"
        class="w-full bg-transparent text-sm sm:text-base outline-none"
        style="color:var(--c-text);font-weight:500"
      >
      <button v-if="toolState.query" type="button" @click="toolState.query = ''" aria-label="Clear search" class="site-search-clear shrink-0 w-7 h-7 rounded-xl flex items-center justify-center text-sm font-bold transition-all">
        <LucideIcon aria-hidden="true" name="x"></LucideIcon>
      </button>
    </div>
  </div>
</template>
