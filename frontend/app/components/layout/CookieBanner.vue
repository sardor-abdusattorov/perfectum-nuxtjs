<script setup lang="ts">
const { visible, accept } = useConsent()
const t = useT()
</script>

<template>
  <ClientOnly>
    <div v-if="visible" class="cookies">
      <p class="cookies__text">
        {{ t('cookie.text', 'Мы используем файлы cookie, чтобы сайт работал лучше. Продолжая пользоваться сайтом, вы соглашаетесь с их использованием.') }}
      </p>
      <button type="button" class="cookies__accept" @click="accept">
        {{ t('cookie.accept', 'Принять') }}
      </button>
    </div>
  </ClientOnly>
</template>
