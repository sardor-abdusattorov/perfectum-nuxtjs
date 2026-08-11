<script setup lang="ts">
const setting = useSetting()
const t = useT()

const stores = computed(() => [
  { name: 'App Store', url: setting('app_store_url', '#'), logo: '/images/apple-dark.svg' },
  { name: 'Google Play', url: setting('google_play_url', '#'), logo: '/images/google-play-dark.svg' },
])
</script>

<template>
  <div class="hero__apps">
    <a
      v-for="store in stores"
      :key="store.name"
      class="hero__store"
      :href="store.url"
      target="_blank"
      rel="noopener"
      :aria-label="store.name"
    >
      <span class="hero__store-text">{{ t('common.store_prefix', 'Скачать в') }}</span>
      <img class="hero__store-logo" :src="store.logo" :alt="store.name" loading="lazy" />
    </a>
  </div>
</template>
