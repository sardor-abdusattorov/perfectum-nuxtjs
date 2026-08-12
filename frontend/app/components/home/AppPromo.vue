<script setup lang="ts">
const block = useBlock('home', 'app_promo')
const stores = useStores()
const t = useT()
</script>

<template>
  <section class="app-promo">
    <div v-if="block.watermark" class="app-promo__watermark">{{ block.watermark }}</div>
    <div class="container">
      <div class="app-promo__wrapper">
        <div class="app-promo__content">
          <h2 class="app-promo__title section__title" v-html="rich(block.title)"></h2>
          <div class="app-promo__description">
            <p v-html="rich(block.description)"></p>
          </div>
          <div v-if="stores.length" class="app-promo__stores">
            <a
              v-for="store in stores"
              :key="store.name"
              :href="store.url"
              class="app-promo__store"
              target="_blank"
              rel="noopener"
            >
              <span class="app-promo__store-text">{{ t('common.store_prefix', 'скачать в') }}</span>
              <img :src="`/images/${store.icon}.svg`" :alt="store.name" class="app-promo__store-logo" loading="lazy" />
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
