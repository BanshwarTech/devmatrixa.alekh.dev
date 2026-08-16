import { createApp } from 'vue';
import ScriptAudit from '../islands/ScriptAudit.vue';

const el = document.getElementById('script-audit-app');
if (el) createApp(ScriptAudit).mount(el);
