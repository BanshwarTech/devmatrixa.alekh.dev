import { createApp } from 'vue';
import CssVariableScanner from '../islands/CssVariableScanner.vue';

const el = document.getElementById('css-variable-scanner-app');
if (el) createApp(CssVariableScanner).mount(el);
