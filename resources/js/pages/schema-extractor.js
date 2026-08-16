import { createApp } from 'vue';
import SchemaExtractor from '../islands/SchemaExtractor.vue';

const el = document.getElementById('schema-extractor-app');
if (el) createApp(SchemaExtractor).mount(el);
