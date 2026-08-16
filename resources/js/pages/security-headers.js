import { createApp } from 'vue';
import SecurityHeaders from '../islands/SecurityHeaders.vue';

const el = document.getElementById('security-headers-app');
if (el) createApp(SecurityHeaders).mount(el);
