<script setup lang="ts">
const block = useBlock('home', 'app_promo')
const setting = useSetting()
const t = useT()
const lines = (value: unknown) => String(value ?? '').split('\n').filter(Boolean)
</script>

<template>
  <section class="app-promo">
      <div v-if="block.watermark" class="app-promo__watermark">{{ block.watermark }}</div>
      <div class="container">
          <div class="app-promo__wrapper">
              <div class="app-promo__content">
                  <h2 class="app-promo__title section__title">
                      <template v-for="(line, index) in lines(block.title)" :key="index">
                          <br v-if="index" />{{ line }}
                      </template>
                  </h2>
                  <div class="app-promo__description">
                      <p>
                          <template v-for="(line, index) in lines(block.description)" :key="index">
                              <br v-if="index" />{{ line }}
                          </template>
                      </p>
                  </div>
                  <div class="app-promo__stores">
                      <a :href="setting('app_store_url', '#')" class="app-promo__store" target="_blank" rel="noopener">
                          <span class="app-promo__store-text">{{ t('common.store_prefix', 'скачать в') }}</span>
                          <img src="/images/apple.svg" alt="App Store" class="app-promo__store-logo" />
                      </a>
                      <a :href="setting('google_play_url', '#')" class="app-promo__store" target="_blank" rel="noopener">
                          <span class="app-promo__store-text">{{ t('common.store_prefix', 'скачать в') }}</span>
                          <img src="/images/google_play.svg" alt="Google Play" class="app-promo__store-logo" />
                      </a>
                  </div>
              </div>
          </div>
      </div>
  </section>
</template>
