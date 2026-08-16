import { createApp } from 'vue';
import ContactForm from '../islands/ContactForm.vue';

const el = document.getElementById('contact-form-app');
if (el) createApp(ContactForm).mount(el);
