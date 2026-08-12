<script setup lang="ts">
import type { Tariff } from '~/composables/useTariffs'

const localePath = useLocalePath()
const t = useT()
const { open } = useTariffModal()

useSeo({ titleKey: 'seo.tariffs' })

const { data } = await useTariffCatalog()

const categories = computed(() => data.value?.categories ?? [])
const tariffs = computed(() => data.value?.tariffs ?? [])

const category = ref('')
const type = ref('')

watchEffect(() => {
  if (!category.value && categories.value.length) {
    category.value = categories.value[0]!.slug
  }
})

const inCategory = computed(() => tariffs.value.filter(item => item.category?.slug === category.value))

const types = computed(() => {
  const present = new Set(inCategory.value.map(item => item.type?.slug).filter(Boolean))

  return (data.value?.types ?? []).filter(item => present.has(item.slug))
})

const visible = computed(() => (
  type.value ? inCategory.value.filter(item => item.type?.slug === type.value) : inCategory.value
))

watch(category, () => {
  type.value = ''
})

function connect(tariff: Tariff): void {
  open({
    name: tariff.name,
    price: tariff.price,
    price_currency: tariff.price_currency,
    price_period: tariff.price_period,
    modal_image: tariff.modal_image,
    ussd: tariff.ussd,
    buttons: tariff.buttons,
  })
}
</script>

<template>
  <section class="page-hero page-hero_tariffs">
    <div class="container">
      <div class="page-hero__inner">
        <p class="page-hero__eyebrow page-hero__eyebrow_silver">{{ t('tariffs.eyebrow') }}</p>
        <h1 class="page-hero__title" v-html="rich(t('tariffs.title'), { accent: 'page-hero__title-red' })"></h1>
      </div>
    </div>
  </section>

  <section class="tariffs-list">
    <div class="container">
      <div v-if="categories.length" class="tariffs-list__tabs" role="tablist" :aria-label="t('tariffs.categories_label')">
        <button
          v-for="item in categories"
          :key="item.slug"
          type="button"
          class="tariffs-list__tab"
          :class="item.slug === category && 'tariffs-list__tab_active'"
          role="tab"
          :aria-selected="item.slug === category"
          @click="category = item.slug"
        >{{ item.name }}</button>
      </div>

      <div class="tariffs-list__filters" role="tablist" :aria-label="t('tariffs.types_label')">
        <button
          type="button"
          class="tariffs-list__filter"
          :class="!type && 'tariffs-list__filter_active'"
          role="tab"
          :aria-selected="!type"
          @click="type = ''"
        >{{ t('tariffs.all') }}</button>

        <button
          v-for="item in types"
          :key="item.slug"
          type="button"
          class="tariffs-list__filter"
          :class="item.slug === type && 'tariffs-list__filter_active'"
          role="tab"
          :aria-selected="item.slug === type"
          @click="type = item.slug"
        >{{ item.name }}</button>
      </div>

      <div class="slider-nav" aria-hidden="true">
        <button type="button" class="slider-arrow slider-arrow_prev" :aria-label="t('common.prev')">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
        <button type="button" class="slider-arrow slider-arrow_next" :aria-label="t('common.next')">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
      </div>

      <div class="tariffs-list__slider swiper">
        <div class="swiper-wrapper">
          <div v-for="tariff in visible" :key="tariff.slug" class="swiper-slide">
            <article class="tariffs-list__card">
              <div v-if="tariff.type" class="tariffs-list__card-head">
                <span class="tariffs-list__card-tag"><b>{{ tariff.type.name }}</b></span>
              </div>

              <div class="tariffs-list__card-body">
                <div class="tariffs-list__price-row">
                  <span class="tariffs-list__brand">{{ tariff.name }}</span>
                  <div class="tariffs-list__price">
                    <span class="tariffs-list__price-value">{{ tariff.price }}</span>
                    <span class="tariffs-list__price-period">{{ tariff.price_currency }} / {{ tariff.price_period }}</span>
                  </div>
                </div>

                <ul v-if="tariff.features.length" class="tariffs-list__feats">
                  <li
                    v-for="(feature, index) in tariff.features"
                    :key="index"
                    class="tariff-feat"
                    :class="index === tariff.features.length - 1 && 'tariff-feat_last'"
                  >
                    <span v-if="feature.icon" class="tariff-feat__icon">
                      <img :src="`/images/icon-${feature.icon}.svg`" alt="" loading="lazy" />
                    </span>
                    <span class="tariff-feat__text" v-html="rich(feature.title)"></span>
                  </li>
                </ul>

                <div class="tariffs-list__actions">
                  <button type="button" class="tariffs-list__connect" @click="connect(tariff)">
                    {{ t('tariffs.connect') }}
                  </button>
                  <NuxtLink class="tariffs-list__more" :to="localePath(`/tariffs/${tariff.slug}`)">
                    {{ t('common.read_more') }}
                  </NuxtLink>
                </div>
              </div>
            </article>
          </div>
        </div>
      </div>

      <div class="tariffs-list__footer">
        <NuxtLink class="tariffs-list__archive" :to="localePath('/tariffs/archive')">
          {{ t('tariffs.archive') }}
          <svg viewBox="0 0 20 16" fill="none">
            <path d="M12 1l7 7-7 7M19 8H1" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </NuxtLink>
      </div>
    </div>
  </section>
</template>
