import { createApp } from 'vue';
import OgPreview from '../islands/OgPreview.vue';

const el = document.getElementById('og-preview-app');
if (el) createApp(OgPreview).mount(el);
