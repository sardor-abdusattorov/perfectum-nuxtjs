<script setup lang="ts">
const localePath = useLocalePath()
const t = useT()

definePageMeta({ layout: 'cdma' })
useSeo({ titleKey: 'seo.cdma_dealers' })

const { data } = await useOffices({ network: 'cdma', type: 'dealer' })

const cards = computed(() => (data.value?.offices ?? [])
  .filter(office => office.region)
  .map(office => ({
    id: office.id,
    name: office.region!.name,
    count: office.dealers_count ?? 0,
  })))
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

  <section class="cdma-regions">
    <div class="container">
      <ul v-if="cards.length" class="cdma-regions__list">
        <li v-for="card in cards" :key="card.id" class="cdma-region-card">
          <NuxtLink class="cdma-region-card__link" :to="localePath(`/cdma/dealers/${card.id}`)">
            <h2 class="cdma-region-card__name">{{ card.name }}</h2>
            <span class="cdma-region-card__count">{{ card.count }} {{ t('offices.dealers_count') }}</span>
            <span class="cdma-region-card__arrow" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </span>
          </NuxtLink>
        </li>
      </ul>

      <p v-else class="cdma-regions__empty">{{ t('offices.dealers_empty') }}</p>
    </div>
  </section>
</template>
