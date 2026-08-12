<script setup lang="ts">
import type { Office } from '~/composables/useOffices'

const route = useRoute()
const localePath = useLocalePath()
const t = useT()

definePageMeta({ layout: 'cdma' })

const slug = computed(() => String(route.params.slug ?? ''))
const { data } = await useOffices({ network: 'cdma', type: 'dealer' })

const region = computed(() => (data.value?.regions ?? []).find(item => item.slug === slug.value) ?? null)

if (!region.value) {
  throw createError({ statusCode: 404, statusMessage: 'Not Found', fatal: true })
}

useSeo({ title: () => region.value?.name ?? '' })

const groups = computed(() => {
  const districts = new Map<string, Office[]>()

  for (const dealer of data.value?.offices ?? []) {
    if (dealer.region?.slug !== slug.value) {
      continue
    }

    const district = dealer.district ?? ''

    districts.set(district, [...(districts.get(district) ?? []), dealer])
  }

  return [...districts].map(([district, items]) => ({ district, items }))
})
</script>

<template>
  <section class="cdma-hero cdma-hero_inner">
    <div class="container">
      <nav class="cdma-crumbs" :aria-label="t('common.breadcrumbs')">
        <NuxtLink class="cdma-crumbs__link" :to="localePath('/cdma')">{{ t('seo.cdma') }}</NuxtLink>
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <span class="cdma-crumbs__current">{{ t('offices.dealers') }}</span>
      </nav>
      <h1 class="cdma-hero__title">{{ t('seo.cdma_dealers') }}</h1>
    </div>
  </section>

  <section class="cdma-dealers">
    <div class="container">
      <NuxtLink class="cdma-dealers__back" :to="localePath('/cdma/dealers')">
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M19 12H5M11 18l-6-6 6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        {{ t('offices.all_regions') }}
      </NuxtLink>

      <h2 class="cdma-dealers__region">{{ region?.name }}</h2>

      <div class="cdma-dealers__table-wrap">
        <table class="cdma-dealers__table">
          <thead class="cdma-dealers__head">
            <tr class="cdma-dealers__row">
              <th class="cdma-dealers__th" scope="col">{{ t('offices.dealer') }}</th>
              <th class="cdma-dealers__th" scope="col">{{ t('offices.address') }}</th>
              <th class="cdma-dealers__th" scope="col">{{ t('offices.phone') }}</th>
            </tr>
          </thead>
          <tbody class="cdma-dealers__body">
            <template v-for="group in groups" :key="group.district">
              <tr v-if="group.district" class="cdma-dealers__row cdma-dealers__row_group">
                <th class="cdma-dealers__group" colspan="3" scope="colgroup">{{ group.district }}</th>
              </tr>
              <tr v-for="dealer in group.items" :key="dealer.id" class="cdma-dealers__row">
                <td class="cdma-dealers__cell">{{ dealer.name }}</td>
                <td class="cdma-dealers__cell">{{ dealer.address }}</td>
                <td class="cdma-dealers__cell">
                  <a v-if="dealer.phone" :href="`tel:${dealer.phone}`">{{ dealer.phone }}</a>
                  <span v-else>—</span>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- CDMA CTA -->
  <section class="cdma-cta cdma-cta_compact">
      <div class="container">
          <h2 class="cdma-cta__title">Готовы к 5G?</h2>
          <p class="cdma-cta__text">Скорости до 1 Гбит/с, VoNR-звонки, eSIM и домашний интернет без
              проводов — всё, чего нет на CDMA.</p>
          <div class="cdma-cta__actions">
              <NuxtLink class="cdma-cta__btn cdma-cta__btn_primary" :to="localePath('/')">Узнать о 5G</NuxtLink>
              <NuxtLink class="cdma-cta__btn cdma-cta__btn_ghost" :to="localePath('/coverage-area')">Проверить покрытие</NuxtLink>
          </div>
      </div>
  </section>
</template>
