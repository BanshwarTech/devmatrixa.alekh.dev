import { createApp } from 'vue';
import LinkChecker from '../islands/LinkChecker.vue';

const el = document.getElementById('link-checker-app');
if (el) createApp(LinkChecker).mount(el);
