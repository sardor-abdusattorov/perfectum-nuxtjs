<script setup lang="ts">
import type { Office, OfficeType } from '~/composables/useOffices'

const PER_PAGE = 16

const localePath = useLocalePath()
const t = useT()
const network = useNetwork()

useSeo({ page: 'offices' })

const { data } = await useOffices({ network })

const offices = computed(() => data.value?.offices ?? [])

const type = ref<OfficeType | ''>('')
const region = ref('')
const city = ref('')
const search = ref('')
const suggestOpen = ref(false)
const page = ref(1)
const active = ref<number | null>(null)
const hint = ref('')
const userCoords = ref<[number, number] | null>(null)
const nearestSort = ref(false)

const searchBox = useTemplateRef('searchBox')
const map = useTemplateRef('map')

const byType = computed(() => (
  type.value ? offices.value.filter(item => item.type === type.value) : offices.value
))

const regions = computed(() => {
  const present = new Set(byType.value.map(item => item.region?.slug).filter(Boolean))

  return (data.value?.regions ?? []).filter(item => present.has(item.slug))
})

const inRegion = computed(() => (
  region.value ? byType.value.filter(item => item.region?.slug === region.value) : byType.value
))

const cities = computed(() => [...new Set(inRegion.value.map(item => item.district).filter(Boolean))] as string[])

function distanceSq(office: Office, coords: [number, number]): number {
  return (office.lat! - coords[0]) ** 2 + (office.lng! - coords[1]) ** 2
}

const visible = computed(() => {
  const items = inRegion.value.filter(item => (
    (!city.value || item.district === city.value) && officeMatches(item, search.value)
  ))

  if (nearestSort.value && userCoords.value) {
    return [...items]
      .filter(item => item.lat !== null && item.lng !== null)
      .sort((a, b) => distanceSq(a, userCoords.value!) - distanceSq(b, userCoords.value!))
  }

  return items
})

const suggestions = computed(() => (
  search.value.trim() ? visible.value.slice(0, 8) : []
))

const pages = computed(() => Math.max(1, Math.ceil(visible.value.length / PER_PAGE)))
const shown = computed(() => visible.value.slice((page.value - 1) * PER_PAGE, page.value * PER_PAGE))

/**
 * Page numbers collapse around the current one — 1 … 4 5 6 … 22 — and the
 * window narrows on a phone so the row never wraps.
 */
const pager = computed<Array<number | null>>(() => {
  const total = pages.value
  const current = page.value
  const delta = 1

  if (total <= 5 + delta * 2) {
    return Array.from({ length: total }, (_, index) => index + 1)
  }

  const items: Array<number | null> = [1]
  const left = Math.max(2, current - delta)
  const right = Math.min(total - 1, current + delta)

  if (left > 2) {
    items.push(null)
  }

  for (let index = left; index <= right; index += 1) {
    items.push(index)
  }

  if (right < total - 1) {
    items.push(null)
  }

  items.push(total)

  return items
})

const counts = computed(() => ({
  office: offices.value.filter(item => item.type === 'office').length,
  dealer: offices.value.filter(item => item.type === 'dealer').length,
}))

watch(type, () => {
  region.value = ''
  city.value = ''
})

watch(region, () => {
  city.value = ''
})

watch([type, region, city, search], () => {
  page.value = 1
})

watch(pages, (total) => {
  page.value = Math.min(page.value, total)
})

function turn(target: number | null): void {
  if (target !== null && target >= 1 && target <= pages.value) {
    page.value = target
  }
}

function select(id: number): void {
  active.value = id
  map.value?.focus(id)
}

function pick(office: Office): void {
  search.value = officeTitle(office)
  suggestOpen.value = false
  select(office.id)
}

function onSearchInput(): void {
  suggestOpen.value = search.value.trim() !== ''
}

function onDocumentClick(event: MouseEvent): void {
  if (!searchBox.value?.contains(event.target as Node)) {
    suggestOpen.value = false
  }
}

onMounted(() => document.addEventListener('click', onDocumentClick))
onBeforeUnmount(() => document.removeEventListener('click', onDocumentClick))

function locate(): void {
  if (!navigator.geolocation) {
    hint.value = t('offices.locate_failed')

    return
  }

  hint.value = t('offices.locating')

  navigator.geolocation.getCurrentPosition(
    (position) => {
      userCoords.value = [position.coords.latitude, position.coords.longitude]
      nearestSort.value = true
      page.value = 1
      hint.value = t('offices.located')

      const nearest = visible.value[0]

      if (nearest) {
        active.value = nearest.id
      }
    },
    () => {
      hint.value = t('offices.locate_failed')
    },
    { enableHighAccuracy: true, timeout: 8000 },
  )
}
</script>

<template>
  <section class="page-hero page-hero_office">
    <div class="container">
      <div class="page-hero__inner">
        <nav class="page-hero__crumbs" :aria-label="t('common.breadcrumbs')">
          <NuxtLink class="page-hero__crumb" :to="localePath('/')">{{ t('common.home') }}</NuxtLink>
          <svg class="page-hero__crumb-sep" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">{{ t('seo.offices') }}</span>
        </nav>
        <p class="page-hero__eyebrow page-hero__eyebrow_silver">{{ t('offices.eyebrow') }}</p>
        <h1 class="page-hero__title section__title" v-html="rich(t('offices.title'), { accent: 'page-hero__title-red' })"></h1>
        <p class="page-hero__subtitle">{{ t('offices.subtitle') }}</p>
      </div>
    </div>
  </section>

  <section class="offices">
    <div class="container">
      <div class="offices__layout">
        <aside class="offices-filter" :aria-label="t('offices.filter_label')">
          <div ref="searchBox" class="offices-filter__search">
            <input
              v-model.trim="search"
              type="search"
              class="offices-filter__search-input"
              :placeholder="t('offices.search_placeholder')"
              :aria-label="t('offices.search')"
              @input="onSearchInput()"
              @focus="onSearchInput()"
              @keydown.esc="suggestOpen = false"
            />
            <button type="button" class="offices-filter__search-btn" :aria-label="t('offices.search')">
              <svg viewBox="0 0 24 24" fill="none">
                <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8" />
                <path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
              </svg>
            </button>

            <div class="offices-filter__suggest" :class="suggestOpen && 'offices-filter__suggest_open'">
              <template v-if="suggestions.length">
                <div
                  v-for="office in suggestions"
                  :key="office.id"
                  class="offices-filter__suggest-item"
                  @click="pick(office)"
                >
                  <span class="offices-filter__suggest-tag" :class="office.type === 'dealer' && 'offices-filter__suggest-tag_dealer'">
                    {{ office.type === 'dealer' ? t('offices.dealer') : t('offices.office') }}
                  </span>
                  <div class="offices-filter__suggest-title">{{ officeTitle(office) }}</div>
                  <div class="offices-filter__suggest-address">{{ office.address }}</div>
                </div>
              </template>
              <div v-else class="offices-filter__suggest-empty">{{ t('offices.empty') }}</div>
            </div>
          </div>

          <div class="offices-filter__tabs" role="tablist" :aria-label="t('offices.type_label')">
            <button
              v-for="tab in ([['', 'offices.all'], ['office', 'offices.offices'], ['dealer', 'offices.dealers']] as const)"
              :key="tab[0]"
              type="button"
              class="offices-filter__tab"
              :class="type === tab[0] && 'offices-filter__tab_active'"
              role="tab"
              :aria-selected="type === tab[0]"
              @click="type = tab[0]"
            >{{ t(tab[1]) }}</button>
          </div>

          <div class="field">
            <label class="field__label" for="offices-region">{{ t('offices.region') }}</label>
            <div class="select">
              <select id="offices-region" v-model="region" class="select__control">
                <option value="">{{ t('offices.all_regions') }}</option>
                <option v-for="item in regions" :key="item.slug" :value="item.slug">{{ item.name }}</option>
              </select>
              <svg class="select__chevron" viewBox="0 0 12 8" fill="none" aria-hidden="true">
                <path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </div>
          </div>

          <div class="field">
            <label class="field__label" for="offices-city">{{ t('offices.city') }}</label>
            <div class="select">
              <select id="offices-city" v-model="city" class="select__control">
                <option value="">{{ t('offices.all_cities') }}</option>
                <option v-for="item in cities" :key="item" :value="item">{{ item }}</option>
              </select>
              <svg class="select__chevron" viewBox="0 0 12 8" fill="none" aria-hidden="true">
                <path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </div>
          </div>

          <button type="button" class="offices-filter__locate" @click="locate()">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M18 6C18 2.691 15.309 0 12 0C8.691 0 6 2.691 6 6C6 8.968 8.166 11.439 11 11.916V23C11 23.552 11.448 24 12 24C12.552 24 13 23.552 13 23V11.916C15.834 11.439 18 8.968 18 6Z" fill="#E60000" />
            </svg>
            {{ t('offices.locate') }}
          </button>
          <p class="offices-filter__hint">{{ hint || t('offices.locate_hint') }}</p>

          <ul class="offices-filter__stats">
            <li class="offices-filter__stat"><b>{{ counts.office }}</b><span>{{ t('offices.offices_count') }}</span></li>
            <li class="offices-filter__stat"><b>{{ counts.dealer }}</b><span>{{ t('offices.dealers_count') }}</span></li>
            <li class="offices-filter__stat"><b>{{ visible.length }}</b><span>{{ t('offices.shown') }}</span></li>
          </ul>
        </aside>

        <OfficesMap ref="map" :points="visible" :user-coords="userCoords" @select="active = $event" />
      </div>

      <div class="offices__head">
        <h2 class="offices__heading">{{ t('offices.found') }}</h2>
        <span class="offices__result">{{ visible.length }}</span>
      </div>

      <div class="offices__list">
        <ul v-if="shown.length" class="offices__grid">
          <li
            v-for="office in shown"
            :key="office.id"
            class="office-card"
            :class="[office.type === 'dealer' && 'office-card_dealer', office.id === active && 'office-card_active']"
            tabindex="0"
            role="button"
            @click="select(office.id)"
            @keydown.enter.prevent="select(office.id)"
            @keydown.space.prevent="select(office.id)"
          >
            <span class="office-card__tag" :class="office.type === 'dealer' && 'office-card__tag_dealer'">
              {{ office.type === 'dealer' ? t('offices.dealer') : t('offices.office') }}
            </span>
            <h3 class="office-card__title">{{ officeTitle(office) }}</h3>
            <p class="office-card__address">{{ office.address }}</p>
          </li>
        </ul>
        <p v-else class="offices__empty">{{ t('offices.empty') }}</p>
      </div>

      <nav v-if="pages > 1" class="vac-pagination" :aria-label="t('offices.pagination_label')">
        <button
          type="button"
          class="vac-pagination__item vac-pagination__item_arrow"
          :class="page === 1 && 'vac-pagination__item_disabled'"
          :aria-label="t('common.prev')"
          @click="turn(page - 1)"
        >
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M19 12H5M11 6l-6 6 6 6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>

        <template v-for="(item, index) in pager" :key="index">
          <span v-if="item === null" class="vac-pagination__ellipsis">…</span>
          <button
            v-else
            type="button"
            class="vac-pagination__item"
            :class="item === page && 'vac-pagination__item_active'"
            @click="turn(item)"
          >{{ item }}</button>
        </template>

        <button
          type="button"
          class="vac-pagination__item vac-pagination__item_arrow"
          :class="page === pages && 'vac-pagination__item_disabled'"
          :aria-label="t('common.next')"
          @click="turn(page + 1)"
        >
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
      </nav>
    </div>
  </section>
</template>
