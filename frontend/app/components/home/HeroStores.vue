<script setup lang="ts">
const stores = useStores()
const t = useT()
</script>

<template>
  <div v-if="stores.length" class="hero__apps">
    <a
      v-for="store in stores"
      :key="store.name"
      class="hero__store"
      :href="store.url"
      target="_blank"
      rel="noopener"
      :aria-label="`${t('common.store_prefix')} ${store.name}`"
    >
      <span class="hero__store-text">{{ t('common.store_prefix') }}</span>
      <img class="hero__store-logo" :src="`/images/${store.icon}-dark.svg`" :alt="store.name"
        :width="store.width" :height="store.height" loading="lazy" />
    </a>
  </div>
</template>
