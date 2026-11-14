<script setup>
import LucideIcon from '../../components/LucideIcon.js';
import { ref, computed, watch } from 'vue';
import { toolState } from './toolStore';

const props = defineProps({
  tools: { type: Array, required: true },
});

const INITIAL_VISIBLE = 8;
const STEP = 4;
const shownCount = ref(INITIAL_VISIBLE);

const CATS = [
  { key: 'all', label: 'All Tools' },
  { key: 'seo', label: 'SEO', icon: 'chart-no-axes-combined', color: '#0098f0' },
  { key: 'code', label: 'Code', icon: 'code', color: '#0694a2' },
  { key: 'web', label: 'Web', icon: 'globe', color: '#7edce2' },
];
const CAT_LABEL = { seo: 'SEO', code: 'Code', web: 'Web' };

const filtered = computed(() => {
  const q = toolState.query.trim().toLowerCase();
  return props.tools.filter((t) => {
    const c = toolState.activeCat === 'all' || t.cat === toolState.activeCat;
    const s = !q || t.name.toLowerCase().includes(q) || t.desc.toLowerCase().includes(q) || t.cat.includes(q);
    return c && s;
  });
});

const visible = computed(() => filtered.value.slice(0, shownCount.value));

watch([() => toolState.activeCat, () => toolState.query], () => {
  shownCount.value = INITIAL_VISIBLE;
});

function countFor(catKey) {
  return catKey === 'all' ? props.tools.length : props.tools.filter((t) => t.cat === catKey).length;
}
</script>

<template>
  <div class="tool-browser-toolbar flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-10">
    <div class="tool-category-bar cat-bar flex flex-wrap gap-2 justify-center lg:justify-start p-2 rounded-full mx-auto lg:mx-0" style="max-width:fit-content">
      <button
        v-for="c in CATS"
        :key="c.key"
        @click="toolState.activeCat = c.key"
        :class="['cpill px-5 py-2 rounded-full text-sm', toolState.activeCat === c.key ? 'active' : '']"
      >
        <LucideIcon v-if="c.icon" class="mr-1.5" :style="{ color: toolState.activeCat === c.key ? '#061c21' : c.color }" :name="c.icon"></LucideIcon>
        {{ c.label }}
        <span class="ml-2 text-[10px] font-bold opacity-70">{{ countFor(c.key) }}</span>
      </button>
    </div>

    <div class="tool-search w-full lg:w-auto lg:min-w-[280px] relative">
      <div class="tool-search-field site-search-field flex items-center gap-3 rounded-2xl px-4 py-3">
        <LucideIcon class="site-search-icon text-sm shrink-0" aria-hidden="true" name="search"></LucideIcon>
        <input
          v-model="toolState.query"
          type="text"
          placeholder="Search tools..."
          aria-label="Search tools"
          autocomplete="off"
          class="w-full bg-transparent text-sm outline-none"
          style="color:var(--c-text);font-weight:500"
        >
        <button v-if="toolState.query" type="button" @click="toolState.query = ''" aria-label="Clear search" class="site-search-clear shrink-0 w-7 h-7 rounded-xl flex items-center justify-center text-sm font-bold transition-all">
          <LucideIcon aria-hidden="true" name="x"></LucideIcon>
        </button>
      </div>
    </div>
  </div>

  <div v-if="filtered.length === 0" class="text-center py-20">
    <div class="text-5xl mb-4 opacity-30">🔩</div>
    <h3 class="font-sans text-xl font-semibold mb-2" style="color:var(--c-muted)">No tools found</h3>
    <p class="text-sm" style="color:var(--c-muted)">Try a different search term or category</p>
  </div>

  <template v-else>
    <div class="tool-card-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
      <a v-for="t in visible" :key="t.id" :href="t.url" class="tool-card rounded-[20px] p-5 flex flex-col relative overflow-hidden cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-teal-400/60" :style="{ '--tool-accent': t.g1, '--tool-accent-secondary': t.g2 }">
        <div class="relative flex items-start justify-between z-10">
          <div class="t-icon-wrap">
            <div class="t-icon w-12 h-12 rounded-2xl flex items-center justify-center relative" :style="{ background: `linear-gradient(135deg,${t.g1},${t.g2})`, boxShadow: `0 10px 24px ${t.g1}55, inset 0 1px 0 rgba(255,255,255,0.35)` }">
              <LucideIcon class="text-[16px] relative z-10" :name="t.icon"></LucideIcon>
              <span class="t-icon-shine"></span>
            </div>
          </div>
          <div class="flex items-center gap-1.5">
            <span class="tc-cat text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-md inline-flex items-center gap-1">
              <span class="w-1 h-1 rounded-full" style="background:currentColor"></span>
              {{ CAT_LABEL[t.cat] }}
            </span>
            <span v-if="t.badge" class="tc-badge text-[9px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider" :style="{ background: `linear-gradient(135deg,${t.g1},${t.g2})`, color: '#061c21' }">
              {{ t.badge }}
            </span>
          </div>
        </div>

        <div class="relative flex-1 mt-4 z-10">
          <h3 class="tc-name font-sans font-700 text-[15.5px] tracking-tight mb-2 leading-snug" style="color:var(--c-text)">{{ t.name }}</h3>
          <p class="text-[12.5px] leading-relaxed line-clamp-3" style="color:var(--c-muted)">{{ t.desc }}</p>
        </div>

        <div class="relative flex items-center justify-between pt-4 mt-4 z-10" style="border-top:1px dashed var(--c-border)">
          <span class="text-[10.5px] flex items-center gap-1.5 font-medium" style="color:var(--c-muted)">
            <template v-if="t.feat"><LucideIcon class="text-[9px]" style="color:#0098f0" name="star"></LucideIcon> Featured</template>
            <template v-else><LucideIcon class="text-[9px]" style="color:#16bdca" name="shield-check"></LucideIcon> Free</template>
          </span>
          <span class="tc-cta text-[11.5px] font-bold inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg" style="color:#0694a2">
            Open
            <LucideIcon class="text-[10px] tc-arrow" name="arrow-right"></LucideIcon>
          </span>
        </div>
      </a>
    </div>

    <div v-if="shownCount < filtered.length" class="flex justify-center mt-8">
      <button @click="shownCount = Math.min(shownCount + STEP, filtered.length)" class="load-more-btn">
        <span>Load more tools</span>
        <span class="load-more-count">{{ filtered.length - shownCount }} left</span>
        <span class="load-more-icon" aria-hidden="true">
          <LucideIcon name="arrow-down"></LucideIcon>
        </span>
      </button>
    </div>
  </template>
</template>
