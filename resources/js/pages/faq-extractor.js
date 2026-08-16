import { createApp } from 'vue';
import FaqExtractor from '../islands/FaqExtractor.vue';

const el = document.getElementById('faq-extractor-app');
if (el) createApp(FaqExtractor).mount(el);
