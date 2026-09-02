<script setup lang="ts">
const localePath = useLocalePath()
const t = useT()
const block = useBlock('home', 'tariffs')

const { data } = await useTariffCatalog()
const { categories, types, category, type, visible } = useTariffFilter(data)

const slider = useTemplateRef('slider')

useSlider(slider, {
  slidesPerView: 2,
  spaceBetween: 24,
  watchOverflow: true,
  navigation: {
    prevEl: '.tariffs .slider-arrow_prev',
    nextEl: '.tariffs .slider-arrow_next',
  },
  breakpoints: {
    0: { slidesPerView: 1.2, spaceBetween: 12 },
    768: { slidesPerView: 2, spaceBetween: 16 },
    1200: { slidesPerView: 3, spaceBetween: 20 },
    1400: { slidesPerView: 4, spaceBetween: 24 },
  },
}, () => visible.value)
</script>

<template>
  <section class="tariffs">
    <div class="container">
      <span v-if="block.eyebrow" class="section__eyebrow">{{ block.eyebrow }}</span>
      <h2 class="section__title" v-html="rich(block.title)"></h2>

      <div class="tariffs__content">
        <div class="tariffs__controls">
          <div class="tariffs__tabs" role="tablist" :aria-label="t('tariffs.categories_label')">
            <button
              v-for="item in categories"
              :key="item.id"
              type="button"
              class="tariffs__tab"
              :class="item.id === category && 'tariffs__tab_active'"
              role="tab"
              :aria-selected="item.id === category"
              @click="category = item.id"
            >{{ item.name }}</button>
          </div>

          <div class="tariffs__chips" role="tablist" :aria-label="t('tariffs.types_label')">
            <button
              type="button"
              class="tariffs__chip"
              :class="!type && 'tariffs__chip_active'"
              role="tab"
              :aria-selected="!type"
              @click="type = ''"
            >{{ t('tariffs.all') }}</button>

            <button
              v-for="item in types"
              :key="item.id"
              type="button"
              class="tariffs__chip"
              :class="item.id === type && 'tariffs__chip_active'"
              role="tab"
              :aria-selected="item.id === type"
              @click="type = item.id"
            >{{ item.name }}</button>
          </div>
        </div>

        <div class="slider-nav">
          <button type="button" class="slider-arrow slider-arrow_prev" :aria-label="t('common.prev')">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </button>
          <button type="button" class="slider-arrow slider-arrow_next" :aria-label="t('common.next')">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </button>
        </div>

        <div ref="slider" class="swiper tariffs__swiper">
          <div class="swiper-wrapper">
            <div v-for="tariff in visible" :key="tariff.slug" class="swiper-slide">
              <article class="tariffs__card">
                <div class="tariffs__card-head">
                  <h3 class="tariffs__name">{{ tariff.name }}</h3>
                  <div v-if="tariff.type" class="tariffs__label">{{ tariff.type.name }}</div>
                </div>

                <div class="tariffs__price">
                  <span class="tariffs__price-value">{{ tariff.price }}</span>
                  <span class="tariffs__price-period">{{ tariff.price_currency }}/{{ tariff.price_period }}</span>
                </div>

                <TariffFeats class="tariffs__feats" :features="tariff.features" />

                <NuxtLink class="tariffs__connect" :to="localePath(`/tariffs/${tariff.slug}`)">
                  {{ t('common.read_more') }}
                </NuxtLink>
              </article>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
