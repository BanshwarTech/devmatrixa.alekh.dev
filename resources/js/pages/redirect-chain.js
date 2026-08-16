import { createApp } from 'vue';
import RedirectChain from '../islands/RedirectChain.vue';

const el = document.getElementById('redirect-chain-app');
if (el) createApp(RedirectChain).mount(el);
