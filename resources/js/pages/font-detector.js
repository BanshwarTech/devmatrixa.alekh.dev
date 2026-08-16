import { createApp } from 'vue';
import FontDetector from '../islands/FontDetector.vue';

const el = document.getElementById('font-detector-app');
if (el) createApp(FontDetector).mount(el);
