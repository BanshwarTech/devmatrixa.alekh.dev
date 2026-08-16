import { createApp } from 'vue';
import PageWeight from '../islands/PageWeight.vue';

const el = document.getElementById('page-weight-app');
if (el) createApp(PageWeight).mount(el);
