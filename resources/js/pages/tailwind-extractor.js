import { createApp } from 'vue';
import TailwindExtractor from '../islands/TailwindExtractor.vue';

const el = document.getElementById('tailwind-extractor-app');
if (el) createApp(TailwindExtractor).mount(el);
