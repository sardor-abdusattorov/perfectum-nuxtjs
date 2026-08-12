<script setup lang="ts">
const { visible, accept } = useConsent()
const t = useT()
</script>

<template>
  <ClientOnly>
    <div v-if="visible" class="cookies">
      <p class="cookies__text">
        {{ t('cookie.text') }}
      </p>
      <button type="button" class="cookies__accept" @click="accept">
        {{ t('cookie.accept') }}
      </button>
    </div>
  </ClientOnly>
</template>
