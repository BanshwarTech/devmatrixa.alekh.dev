import { createApp } from 'vue';
import DnsLookup from '../islands/DnsLookup.vue';

const el = document.getElementById('dns-lookup-app');
if (el) createApp(DnsLookup).mount(el);
