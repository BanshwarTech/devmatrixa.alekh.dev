import { createApp } from 'vue';
import SeoAnalyzer from '../islands/SeoAnalyzer.vue';

const el = document.getElementById('seo-analyzer-app');
if (el) createApp(SeoAnalyzer).mount(el);
