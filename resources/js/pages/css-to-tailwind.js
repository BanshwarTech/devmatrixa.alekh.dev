import { createApp } from 'vue';
import CssToTailwind from '../islands/CssToTailwind.vue';

const el = document.getElementById('css-to-tailwind-app');
if (el) createApp(CssToTailwind).mount(el);
