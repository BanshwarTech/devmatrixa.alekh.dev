import { createApp } from 'vue';
import TypographySeoChecker from '../islands/TypographySeoChecker.vue';

const el = document.getElementById('typography-seo-checker-app');
if (el) createApp(TypographySeoChecker).mount(el);
