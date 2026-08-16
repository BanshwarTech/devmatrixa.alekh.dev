<script setup>
defineProps({
  value: { type: [String, Number], required: true },
  label: { type: String, required: true },
  color: { type: String, default: null },
  active: { type: Boolean, default: false },
  clickable: { type: Boolean, default: false },
});
defineEmits(['click']);
</script>

<template>
  <button
    v-if="clickable"
    type="button"
    @click="$emit('click')"
    :aria-pressed="active"
    class="glass rounded-2xl p-4 text-center w-full cursor-pointer hover:scale-[1.02] active:scale-[0.98] transition-transform"
    :style="active ? { boxShadow: `0 0 0 2px ${color ?? '#0694a2'}, 0 0 16px ${color ?? '#0694a2'}33` } : {}"
  >
    <p class="font-sans text-2xl font-700" :style="color ? { color } : {}">{{ value }}</p>
    <p class="text-[10px] uppercase tracking-widest font-semibold" style="color:var(--c-muted)">{{ label }}</p>
  </button>
  <div v-else class="glass rounded-2xl p-4 text-center">
    <p class="font-sans text-2xl font-700" :style="color ? { color } : {}">{{ value }}</p>
    <p class="text-[10px] uppercase tracking-widest font-semibold" style="color:var(--c-muted)">{{ label }}</p>
  </div>
</template>
