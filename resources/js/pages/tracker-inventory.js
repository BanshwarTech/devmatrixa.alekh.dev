import { createApp } from 'vue';
import TrackerInventory from '../islands/TrackerInventory.vue';

const el = document.getElementById('tracker-inventory-app');
if (el) createApp(TrackerInventory).mount(el);
