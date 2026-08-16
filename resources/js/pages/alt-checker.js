import { createApp } from 'vue';
import AltChecker from '../islands/AltChecker.vue';

const el = document.getElementById('alt-checker-app');
if (el) createApp(AltChecker).mount(el);
