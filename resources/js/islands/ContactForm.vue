<script setup>
import { ref, computed } from 'vue';

const name = ref('');
const email = ref('');
const subject = ref('');
const message = ref('');

const status = ref('idle'); // idle | sending | sent | error
const errorMessage = ref('');

const MAX_LENGTH = 2000;
const messageLength = computed(() => message.value.length);

const EMAIL_PATTERN = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

function validate() {
  if (!name.value.trim() || !email.value.trim() || !subject.value.trim() || !message.value.trim()) {
    return 'Please fill in all required fields.';
  }
  if (!EMAIL_PATTERN.test(email.value.trim())) {
    return 'Please enter a valid email address.';
  }
  return '';
}

function onSubmit() {
  const validationError = validate();
  if (validationError) {
    errorMessage.value = validationError;
    status.value = 'error';
    return;
  }

  status.value = 'sending';
  errorMessage.value = '';

  try {
    const mailSubject = encodeURIComponent(subject.value || `Devmatrixa contact from ${name.value}`);
    const body = encodeURIComponent(`${message.value}\n\n- ${name.value} (${email.value})`);
    window.location.href = `mailto:contact@alekh.dev?subject=${mailSubject}&body=${body}`;
    status.value = 'sent';
  } catch {
    status.value = 'error';
    errorMessage.value = 'Could not open your mail client. Please email contact@alekh.dev directly.';
  }
}
</script>

<template>
  <form @submit.prevent="onSubmit" class="glass rounded-[28px] p-6 shadow-2xl shadow-teal-950/20 sm:p-8 lg:p-10">
    <div class="mb-7">
      <h2 class="text-2xl font-600 tracking-tight sm:text-3xl">Send a Message</h2>
      <p class="mt-2 text-sm sm:text-base" style="color:var(--c-muted)">
        Fill in the form below and we will get back to you.
      </p>
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
      <div>
        <label class="mb-2 block text-xs uppercase tracking-widest font-700" style="color:var(--c-muted)">
          Your name <span style="color:#16bdca">*</span>
        </label>
        <input
          v-model="name"
          required
          placeholder="John Doe"
          class="field w-full rounded-2xl px-5 py-3.5 text-sm"
        >
      </div>
      <div>
        <label class="mb-2 block text-xs uppercase tracking-widest font-700" style="color:var(--c-muted)">
          Email address <span style="color:#16bdca">*</span>
        </label>
        <input
          v-model="email"
          required
          type="email"
          placeholder="john@example.com"
          class="field w-full rounded-2xl px-5 py-3.5 text-sm"
        >
      </div>
    </div>

    <div class="mt-5">
      <label class="mb-2 block text-xs uppercase tracking-widest font-700" style="color:var(--c-muted)">
        Subject <span style="color:#16bdca">*</span>
      </label>
      <input
        v-model="subject"
        required
        placeholder="Bug report / Tool idea / Other ..."
        class="field w-full rounded-2xl px-5 py-3.5 text-sm"
      >
    </div>

    <div class="mt-5">
      <label class="mb-2 block text-xs uppercase tracking-widest font-700" style="color:var(--c-muted)">
        Message <span style="color:#16bdca">*</span>
      </label>
      <textarea
        v-model="message"
        required
        :maxlength="MAX_LENGTH"
        rows="7"
        placeholder="Describe your bug, idea, or question in detail..."
        class="field w-full resize-y rounded-2xl px-5 py-4 text-sm"
      ></textarea>
      <p class="mt-2 text-right text-xs font-600" style="color:var(--c-muted)">
        {{ messageLength }} / {{ MAX_LENGTH }}
      </p>
    </div>

    <button
      type="submit"
      :disabled="status === 'sending'"
      class="btn-primary mt-4 inline-flex w-full items-center justify-center gap-2 rounded-2xl px-6 py-4 text-sm font-700 disabled:opacity-70"
    >
      <i class="fa-solid fa-paper-plane text-xs" style="color:#061c21"></i>
      {{ status === 'sending' ? 'Opening mail client...' : 'Send Message' }}
    </button>

    <p v-if="status === 'sent'" class="mt-4 text-center text-xs font-600" style="color:#65a30d">
      Your mail client should have opened. Thanks!
    </p>
    <p v-if="status === 'error'" class="mt-4 text-center text-xs font-600" style="color:#f87171">
      {{ errorMessage }}
    </p>
  </form>
</template>
