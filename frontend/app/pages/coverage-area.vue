<script setup lang="ts">
await useBlocks('coverage_area')

const hero = useBlock('coverage_area', 'page_hero')
const { data: layers } = await useCoverage()

const t = useT()

useSeo({ page: 'coverage_area', titleKey: 'seo.coverage' })

const CITIES: Record<string, [number, number]> = {
  tashkent: [41.311, 69.24],
  samarkand: [39.654, 66.96],
  bukhara: [39.767, 64.421],
  nukus: [42.46, 59.617],
  urgench: [41.55, 60.631],
}

const available = computed(() => layers.value ?? [])
const active = ref('')
const city = ref('tashkent')

watchEffect(() => {
  if (!active.value && available.value.length) {
    active.value = available.value[0]!.key
  }
})

const center = computed(() => CITIES[city.value] ?? null)
</script>

<template>
  <PageHero
    variant="page-hero_inner page-hero_coverage"
    :crumb="t('seo.coverage')"
    :eyebrow="hero.eyebrow"
    eyebrow-silver
    :title="rich(hero.title, { accent: 'page-hero__title-red' })"
    :subtitle="hero.subtitle"
  />

  <section class="coverage coverage_map">
    <div class="container">
      <form class="coverage-search" @submit.prevent>
        <p class="coverage-search__label">{{ t('coverage.search_label') }}</p>
        <div class="coverage-search__controls">
          <div class="select coverage-search__select">
            <select v-model="city" class="select__control" :aria-label="t('coverage.city')">
              <option v-for="(coords, key) in CITIES" :key="key" :value="key">{{ t(`coverage.city_${key}`) }}</option>
            </select>
            <svg class="select__chevron" viewBox="0 0 12 8" fill="none" aria-hidden="true">
              <path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </div>

          <div v-if="available.length > 1" class="coverage-search__layers">
            <button
              v-for="layer in available"
              :key="layer.key"
              type="button"
              class="coverage-search__layer"
              :class="layer.key === active && 'coverage-search__layer_active'"
              :style="{ '--layer-color': layer.color }"
              @click="active = layer.key"
            >{{ layer.name }}</button>
          </div>
        </div>
      </form>

      <CoverageMap :layers="available" :active="active" :center="center" />

      <p v-if="!available.length" class="coverage__empty">{{ t('coverage.empty') }}</p>
    </div>
  </section>
</template>
