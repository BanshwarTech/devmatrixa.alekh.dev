import { createApp } from 'vue';
import AnchorTextAnalyzer from '../islands/AnchorTextAnalyzer.vue';

const el = document.getElementById('anchor-text-analyzer-app');
if (el) createApp(AnchorTextAnalyzer).mount(el);
