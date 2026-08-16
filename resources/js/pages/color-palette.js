import { createApp } from 'vue';
import ColorPalette from '../islands/ColorPalette.vue';

const el = document.getElementById('color-palette-app');
if (el) createApp(ColorPalette).mount(el);
