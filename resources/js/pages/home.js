import { createApp } from 'vue';
import HeroSearch from '../islands/home/HeroSearch.vue';
import ToolBrowser from '../islands/home/ToolBrowser.vue';

const searchEl = document.getElementById('hero-search-app');
if (searchEl) createApp(HeroSearch).mount(searchEl);

const browserEl = document.getElementById('tool-browser-app');
if (browserEl) {
  createApp(ToolBrowser, { tools: JSON.parse(browserEl.dataset.tools) }).mount(browserEl);
}
