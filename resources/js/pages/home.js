import { createApp } from 'vue';
import ToolBrowser from '../islands/home/ToolBrowser.vue';

const browserEl = document.getElementById('tool-browser-app');
if (browserEl) {
  createApp(ToolBrowser, { tools: JSON.parse(browserEl.dataset.tools) }).mount(browserEl);
}
