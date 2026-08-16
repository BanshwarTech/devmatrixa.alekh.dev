import { createApp } from 'vue';
import SitemapDiff from '../islands/SitemapDiff.vue';

const el = document.getElementById('sitemap-diff-app');
if (el) createApp(SitemapDiff).mount(el);
