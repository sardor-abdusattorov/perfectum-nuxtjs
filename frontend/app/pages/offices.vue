<script setup lang="ts">
import type { Office, OfficeType } from '~/composables/useOffices'

const PER_PAGE = 16

const localePath = useLocalePath()
const t = useT()
const network = useNetwork()

useSeo({ page: 'offices' })

const { data } = await useOffices({ network })

const regions = computed(() => data.value?.regions ?? [])
const offices = computed(() => data.value?.offices ?? [])

const type = ref<OfficeType | ''>('')
const region = ref('')
const district = ref('')
const search = ref('')
const page = ref(1)
const active = ref<number | null>(null)
const hint = ref('')

const inRegion = computed(() => (
  region.value ? offices.value.filter(item => item.region?.slug === region.value) : offices.value
))

const districts = computed(() => [...new Set(inRegion.value.map(item => item.district).filter(Boolean))] as string[])

const visible = computed(() => inRegion.value.filter(item => (
  (!type.value || item.type === type.value)
  && (!district.value || item.district === district.value)
  && officeMatches(item, search.value)
)))

const pages = computed(() => Math.max(1, Math.ceil(visible.value.length / PER_PAGE)))
const shown = computed(() => visible.value.slice((page.value - 1) * PER_PAGE, page.value * PER_PAGE))

const pager = computed<Array<number | null>>(() => {
  const items: Array<number | null> = []

  for (let index = 1; index <= pages.value; index += 1) {
    if (index === 1 || index === pages.value || Math.abs(index - page.value) <= 1) {
      items.push(index)
    }
    else if (items.at(-1) !== null) {
      items.push(null)
    }
  }

  return items
})

const counts = computed(() => ({
  office: offices.value.filter(item => item.type === 'office').length,
  dealer: offices.value.filter(item => item.type === 'dealer').length,
}))

watch([type, region, district, search], () => {
  page.value = 1
})

watch(region, () => {
  district.value = ''
})

watch(pages, total => {
  page.value = Math.min(page.value, total)
})

function turn(target: number | null): void {
  if (target !== null && target >= 1 && target <= pages.value) {
    page.value = target
  }
}

function select(id: number): void {
  active.value = id
}

function locate(): void {
  if (!navigator.geolocation) {
    hint.value = t('offices.locate_unsupported')

    return
  }

  hint.value = t('offices.locating')

  navigator.geolocation.getCurrentPosition(
    position => {
      const { latitude, longitude } = position.coords
      const distance = (item: Office) => (item.lat! - latitude) ** 2 + (item.lng! - longitude) ** 2
      const nearest = visible.value.filter(item => item.lat !== null && item.lng !== null).sort((a, b) => distance(a) - distance(b))[0]

      hint.value = nearest ? t('offices.located') : t('offices.locate_empty')

      if (nearest) {
        page.value = Math.floor(visible.value.indexOf(nearest) / PER_PAGE) + 1
        active.value = nearest.id
      }
    },
    () => {
      hint.value = t('offices.locate_failed')
    },
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
          <div class="offices-filter__search">
            <input
              v-model.trim="search"
              type="search"
              class="offices-filter__search-input"
              :placeholder="t('offices.search_placeholder')"
              :aria-label="t('offices.search')"
            />
            <span class="offices-filter__search-btn" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none">
                <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8" />
                <path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
              </svg>
            </span>
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
            <label class="field__label" for="offices-district">{{ t('offices.district') }}</label>
            <div class="select">
              <select id="offices-district" v-model="district" class="select__control">
                <option value="">{{ t('offices.all_districts') }}</option>
                <option v-for="item in districts" :key="item" :value="item">{{ item }}</option>
              </select>
              <svg class="select__chevron" viewBox="0 0 12 8" fill="none" aria-hidden="true">
                <path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </div>
          </div>

          <button type="button" class="offices-filter__locate" @click="locate()">
            <svg width="12" height="24" viewBox="0 0 12 24" fill="none" aria-hidden="true">
              <path d="M12 6C12 2.691 9.309 0 6 0C2.691 0 0 2.691 0 6C0 8.968 2.166 11.439 5 11.916V23C5 23.552 5.448 24 6 24C6.552 24 7 23.552 7 23V11.916C9.834 11.439 12 8.968 12 6Z" fill="#E60000" />
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

        <OfficesMap :points="visible" :active="active" @select="select" />
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
            <a v-if="office.phone" class="office-card__phone" :href="`tel:${office.phone}`" @click.stop>{{ office.phone }}</a>
          </li>
        </ul>
        <p v-else class="offices__empty">{{ t('offices.empty') }}</p>
      </div>

      <nav v-if="pages > 1" class="vac-pagination" :aria-label="t('offices.pagination_label')">
        <button
          type="button"
          class="vac-pagination__item"
          :class="page === 1 && 'vac-pagination__item_disabled'"
          :aria-label="t('common.prev')"
          @click="turn(page - 1)"
        >
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
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
          class="vac-pagination__item"
          :class="page === pages && 'vac-pagination__item_disabled'"
          :aria-label="t('common.next')"
          @click="turn(page + 1)"
        >
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
      </nav>
    </div>
  </section>
</template>
