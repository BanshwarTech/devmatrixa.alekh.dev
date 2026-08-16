<script setup>
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
  { key: 'seo', label: 'SEO', icon: 'fa-magnifying-glass-chart', color: '#a3e635' },
  { key: 'code', label: 'Code', icon: 'fa-code', color: '#0694a2' },
  { key: 'web', label: 'Web', icon: 'fa-globe', color: '#7edce2' },
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

function iconColor(t) {
  return t.g1 === '#a3e635' || t.g2 === '#a3e635' ? '#061c21' : '#fff';
}

function countFor(catKey) {
  return catKey === 'all' ? props.tools.length : props.tools.filter((t) => t.cat === catKey).length;
}
</script>

<template>
  <div class="cat-bar flex flex-wrap gap-2 justify-center p-2 rounded-full mx-auto mb-10" style="max-width:fit-content">
    <button
      v-for="c in CATS"
      :key="c.key"
      @click="toolState.activeCat = c.key"
      :class="['cpill px-5 py-2 rounded-full text-sm', toolState.activeCat === c.key ? 'active' : '']"
    >
      <i v-if="c.icon" class="fa-solid mr-1.5" :class="c.icon" :style="{ color: toolState.activeCat === c.key ? '#061c21' : c.color }"></i>
      {{ c.label }}
      <span class="ml-2 text-[10px] font-bold opacity-70">{{ countFor(c.key) }}</span>
    </button>
  </div>

  <div v-if="filtered.length === 0" class="text-center py-20">
    <div class="text-5xl mb-4 opacity-30">🔩</div>
    <h3 class="font-sans text-xl font-semibold mb-2" style="color:var(--c-muted)">No tools found</h3>
    <p class="text-sm" style="color:var(--c-muted)">Try a different search term or category</p>
  </div>

  <template v-else>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
      <a v-for="t in visible" :key="t.id" :href="t.url" class="tool-card rounded-[20px] p-5 flex flex-col relative overflow-hidden cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-teal-400/60">
        <div class="relative flex items-start justify-between z-10">
          <div class="t-icon-wrap">
            <div class="t-icon w-12 h-12 rounded-2xl flex items-center justify-center relative" :style="{ background: `linear-gradient(135deg,${t.g1},${t.g2})`, boxShadow: `0 10px 24px ${t.g1}55, inset 0 1px 0 rgba(255,255,255,0.35)` }">
              <i :class="t.icon" class="text-[16px] relative z-10" :style="{ color: iconColor(t) }"></i>
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
            <template v-if="t.feat"><i class="fa-solid fa-star text-[9px]" style="color:#a3e635"></i> Featured</template>
            <template v-else><i class="fa-solid fa-shield-halved text-[9px]" style="color:#16bdca"></i> Free</template>
          </span>
          <span class="tc-cta text-[11.5px] font-bold inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg" style="color:#0694a2">
            Open
            <i class="fa-solid fa-arrow-right text-[10px] tc-arrow"></i>
          </span>
        </div>
      </a>
    </div>

    <div v-if="shownCount < filtered.length" class="flex justify-center mt-8">
      <button @click="shownCount = Math.min(shownCount + STEP, filtered.length)" class="btn-outline px-7 py-3 rounded-full text-sm font-semibold inline-flex items-center gap-2.5">
        Load More ({{ filtered.length - shownCount }} left)
        <i class="fa-solid fa-arrow-down text-xs" style="color:var(--c-muted)"></i>
      </button>
    </div>
  </template>
</template>
