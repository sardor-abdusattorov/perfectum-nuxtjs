<script setup lang="ts">
const block = useBlock('home', 'app_promo')
const setting = useSetting()
const t = useT()

const stores = computed(() => [
  { name: 'App Store', url: setting('app_store_url', '#'), logo: '/images/apple.svg' },
  { name: 'Google Play', url: setting('google_play_url', '#'), logo: '/images/google_play.svg' },
])
</script>

<template>
  <section class="app-promo">
    <div v-if="block.watermark" class="app-promo__watermark">{{ block.watermark }}</div>
    <div class="container">
      <div class="app-promo__wrapper">
        <div class="app-promo__content">
          <h2 class="app-promo__title section__title"><LayoutLines :value="block.title" /></h2>
          <div class="app-promo__description">
            <p><LayoutLines :value="block.description" /></p>
          </div>
          <div class="app-promo__stores">
            <a
              v-for="store in stores"
              :key="store.name"
              :href="store.url"
              class="app-promo__store"
              target="_blank"
              rel="noopener"
            >
              <span class="app-promo__store-text">{{ t('common.store_prefix', 'скачать в') }}</span>
              <img :src="store.logo" :alt="store.name" class="app-promo__store-logo" />
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
