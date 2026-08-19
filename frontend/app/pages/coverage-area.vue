<script setup lang="ts">
await useBlocks('coverage_area')

const hero = useBlock('coverage_area', 'page_hero')
const { data: coverage } = await useCoverage()

const t = useT()

useSeo({ page: 'coverage_area', titleKey: 'seo.coverage' })

const available = computed(() => coverage.value?.layers ?? [])
const cities = computed(() => coverage.value?.cities ?? [])
const active = ref('')
const city = ref<number | ''>('')

watchEffect(() => {
  if (!active.value && available.value.length) {
    active.value = available.value[0]!.key
  }

  if (!city.value && cities.value.length) {
    city.value = cities.value[0]!.id
  }
})

const center = computed(() => cities.value.find(item => item.id === city.value)?.center ?? null)

const map = useTemplateRef('map')
const field = useTemplateRef('field')
const address = ref('')
const open = ref(false)
const missing = ref(false)
const broken = ref(false)

/**
 * Collapsed, the round button is the handle that opens the field; once there is
 * something to look for it becomes the search itself.
 */
async function find(): Promise<void> {
  if (!address.value.trim()) {
    open.value = true
    field.value?.focus()

    return
  }

  missing.value = !(await map.value?.find(address.value))
}

function clear(): void {
  if (!address.value) {
    open.value = false

    return
  }

  address.value = ''
  missing.value = false
  field.value?.focus()
}
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
      <form class="coverage-search" :class="open && 'coverage-search_searching'" @submit.prevent="find()">
        <p class="coverage-search__label">{{ t('coverage.search_label') }}</p>
        <div class="coverage-search__controls">
          <div class="coverage-search__find" :class="open && 'coverage-search__find_open'">
            <div class="coverage-search__field">
              <input
                ref="field"
                v-model="address"
                class="coverage-search__input"
                type="search"
                :placeholder="t('coverage.address_placeholder')"
                :aria-label="t('coverage.address')"
                @focus="open = true"
              />
              <button
                type="button"
                class="coverage-search__close"
                :aria-label="t('coverage.address_clear')"
                @click="clear()"
              >
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                </svg>
              </button>
            </div>
            <button class="coverage-search__btn" type="submit" :aria-label="t('coverage.address')">
              <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8" />
                <path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
              </svg>
            </button>
          </div>

          <div v-if="cities.length" class="select coverage-search__select">
            <select v-model="city" class="select__control" :aria-label="t('coverage.city')">
              <option v-for="item in cities" :key="item.id" :value="item.id">{{ item.name }}</option>
            </select>
            <svg class="select__chevron" viewBox="0 0 12 8" fill="none" aria-hidden="true">
              <path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </div>

          <div v-if="available.length" class="coverage-search__layers" role="radiogroup" :aria-label="t('coverage.network', 'Тип сети')">
            <button
              v-for="layer in available"
              :key="layer.key"
              type="button"
              role="radio"
              :aria-checked="layer.key === active"
              class="coverage-search__layer"
              :class="layer.key === active && 'coverage-search__layer_active'"
              :style="{ '--layer-color': layer.color }"
              @click="active = layer.key"
            >{{ layer.name }}</button>
          </div>
        </div>
      </form>

      <p v-if="missing" class="coverage-search__missing">{{ t('coverage.address_not_found') }}</p>

      <CoverageMap ref="map" :layers="available" :active="active" :center="center" @failed="broken = $event" />

      <p v-if="!available.length" class="coverage__empty">{{ t('coverage.empty') }}</p>
      <p v-else-if="broken" class="coverage__empty">{{ t('coverage.map_unavailable') }}</p>
    </div>
  </section>
</template>
