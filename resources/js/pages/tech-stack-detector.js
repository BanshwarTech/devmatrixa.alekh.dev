import { createApp } from 'vue';
import TechStackDetector from '../islands/TechStackDetector.vue';

const el = document.getElementById('tech-stack-detector-app');
if (el) createApp(TechStackDetector).mount(el);
