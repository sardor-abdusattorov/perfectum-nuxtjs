<script setup lang="ts">
import type { TariffFeature } from '~/composables/useTariffs'

const localePath = useLocalePath()
const route = useRoute()
const t = useT()
const { open } = useTariffModal()

definePageMeta({ layout: 'cdma' })

const slug = computed(() => String(route.params.slug ?? ''))

const { data: tariff } = await useTariff(slug)

if (!tariff.value) {
  throw createError({ statusCode: 404, statusMessage: 'Not Found', fatal: true })
}

useSeo({ title: () => tariff.value?.name ?? '' })

function connect(): void {
  if (!tariff.value) {
    return
  }

  open({
    name: tariff.value.name,
    price: tariff.value.price,
    price_currency: tariff.value.price_currency,
    price_period: tariff.value.price_period,
    modal_image: tariff.value.modal_image,
    buttons: tariff.value.buttons,
  })
}

function featureCaption(feature: TariffFeature): string {
  return feature.note.replace(/^\(/, '').replace(/\)$/, '')
}
</script>

<template>
  <template v-if="tariff">
      
      <section class="cdma-hero cdma-hero_slim">
          <div class="container">
              <nav class="cdma-crumbs" :aria-label="t('common.breadcrumbs')">
                  <NuxtLink class="cdma-crumbs__link" :to="localePath('/cdma')">{{ t('seo.cdma') }}</NuxtLink>
                  <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <NuxtLink class="cdma-crumbs__link" :to="localePath('/cdma') + '#cdma-tariffs'">{{ t('cdma.tariffs_title') }}</NuxtLink>
                  <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <span class="cdma-crumbs__current">{{ tariff.name }}</span>
              </nav>
              <h1 class="cdma-hero__title">{{ tariff.name }}</h1>
          </div>
      </section>

<section class="cdma-detail">
          <div class="container">
              <span v-if="tariff.type" class="cdma-badge">{{ tariff.type.name }}</span>

              <div class="cdma-price">
                  <p class="cdma-price__value">{{ tariff.price }}<span class="cdma-price__period">{{ tariff.price_currency }} / {{ tariff.price_period }}</span></p>
                  <button type="button" class="cdma-price__btn" @click="connect()">{{ t('cdma.connect') }}</button>
              </div>

              <template v-if="tariff.features.length">
                  <h2 class="cdma-detail__subhead">{{ t('cdma.tariff_includes') }}</h2>
                  <ul class="cdma-features">
                      <li v-for="(feature, index) in tariff.features" :key="index" class="cdma-feature">
                          <span v-if="feature.icon" class="cdma-feature__icon" aria-hidden="true">
                              <img :src="`/images/icon-${feature.icon}.svg`" alt="" loading="lazy" />
                          </span>
                          <span v-if="featureCaption(feature)" class="cdma-feature__label">{{ featureCaption(feature) }}</span>
                          <span class="cdma-feature__value">{{ feature.title }}</span>
                      </li>
                  </ul>
              </template>

              <template v-for="(description, index) in tariff.descriptions" :key="index">
                  <h2 class="cdma-detail__subhead">{{ description.name }}</h2>
                  <div class="cdma-prose rich" v-html="description.content"></div>
              </template>

              <NuxtLink class="cdma-back" :to="localePath('/cdma') + '#cdma-tariffs'">
                  <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M19 12H5M11 18l-6-6 6-6" stroke="currentColor" stroke-width="1.8"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  {{ t('cdma.all_tariffs') }}
              </NuxtLink>
          </div>
      </section>

<CdmaSectionFooter />
  </template>
</template>
