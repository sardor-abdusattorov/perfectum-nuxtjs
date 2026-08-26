<script setup lang="ts">
const route = useRoute()
const localePath = useLocalePath()
const t = useT()

definePageMeta({ layout: 'cdma' })

const id = computed(() => Number(route.params.slug))
const { data } = await useOffices({ network: 'cdma', type: 'dealer' })

const entry = computed(() => (data.value?.offices ?? []).find(office => office.id === id.value) ?? null)

if (!entry.value) {
  throw createError({ statusCode: 404, statusMessage: 'Not Found', fatal: true })
}

useSeo({ title: () => entry.value?.region?.name ?? '' })
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

  <section v-if="entry" class="cdma-dealers">
    <div class="container">
      <NuxtLink class="cdma-dealers__back" :to="localePath('/cdma/dealers')">
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M19 12H5M11 18l-6-6 6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        {{ t('offices.all_regions') }}
      </NuxtLink>

      <h2 class="cdma-dealers__region">{{ entry.region?.name }}</h2>

      <div v-if="entry.content" class="cdma-dealers__content rich" v-html="entry.content"></div>
      <p v-else class="cdma-regions__empty">{{ t('offices.dealers_empty') }}</p>
    </div>
  </section>
</template>
