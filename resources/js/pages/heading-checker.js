import { createApp } from 'vue';
import HeadingChecker from '../islands/HeadingChecker.vue';

const el = document.getElementById('heading-checker-app');
if (el) createApp(HeadingChecker).mount(el);
