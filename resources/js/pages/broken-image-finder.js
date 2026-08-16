import { createApp } from 'vue';
import BrokenImageFinder from '../islands/BrokenImageFinder.vue';

const el = document.getElementById('broken-image-finder-app');
if (el) createApp(BrokenImageFinder).mount(el);
