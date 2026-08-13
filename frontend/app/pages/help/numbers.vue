<script setup lang="ts">
import type { ApiResponse } from '~/types/api'

interface CdmaFilterOption {
  name: string
  value: string
}

interface CdmaPrice {
  code: number
  price: number
}

interface CdmaFilters {
  prefixes: CdmaFilterOption[]
  prices: CdmaPrice[]
}

interface CdmaNumber {
  number: string
  prefix: string
  price: number
  date: string
}

interface CdmaPayload {
  numbers: CdmaNumber[]
  total: number
  page: number
  totalPages: number
}

const localePath = useLocalePath()
const t = useT()
const { $api } = useNuxtApp()

useSeo({ titleKey: 'seo.numbers' })

const filters = ref<CdmaFilters>({ prefixes: [], prices: [] })
const prefix = ref('')
const price = ref(-1)
const number = ref('')
const numberInvalid = ref(false)

const data = ref<CdmaPayload | null>(null)
const initialLoading = ref(true)
const busy = ref(false)
const searched = ref(false)

/**
 * The page renders at once and the filters load behind the preloader,
 * so a slow gateway never blocks navigation.
 */
onMounted(async () => {
  try {
    const response = await $api<ApiResponse<CdmaFilters>>('/cdma-numbers/filters')

    filters.value = response.data
  }
  catch {
    filters.value = { prefixes: [], prices: [] }
  }
  finally {
    initialLoading.value = false
  }
})

const numbers = computed(() => data.value?.numbers ?? [])
const totalPages = computed(() => data.value?.totalPages ?? 1)
const page = computed(() => data.value?.page ?? 1)

async function search(target = 1): Promise<void> {
  numberInvalid.value = !/^\d{1,4}$/.test(number.value)

  if (numberInvalid.value || busy.value) {
    return
  }

  busy.value = true

  try {
    const response = await $api<ApiResponse<CdmaPayload>>('/cdma-numbers', {
      method: 'POST',
      body: {
        number: number.value,
        prefix: prefix.value || undefined,
        price: price.value,
        page: target,
      },
    })

    data.value = response.data
  }
  catch {
    data.value = null
  }
  finally {
    busy.value = false
    searched.value = true
  }
}

function onNumber(event: Event): void {
  const input = event.target as HTMLInputElement

  number.value = input.value.replace(/\D/g, '').slice(0, 4)
  input.value = number.value

  if (number.value) {
    numberInvalid.value = false
  }
}

function spaces(value: number): string {
  return String(value).replace(/\B(?=(\d{3})+(?!\d))/g, ' ')
}
</script>

<template>
  <section class="page-hero page-hero_inner page-hero_help">
    <div class="container">
      <div class="page-hero__inner">
        <nav class="page-hero__crumbs" :aria-label="t('common.breadcrumbs')">
          <NuxtLink class="page-hero__crumb" :to="localePath('/')">{{ t('common.home') }}</NuxtLink>
          <svg class="page-hero__crumb-sep" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">{{ t('help.nav_numbers') }}</span>
        </nav>
        <h1 class="page-hero__title section__title" v-html="rich(t('help.numbers_title'), { accent: 'page-hero__title-red' })"></h1>
      </div>
    </div>
  </section>

  <section class="help">
    <div class="container">
      <div class="help__panel">
        <HelpAside active="numbers" />

        <div class="help__content">
          <div class="help__tab help__tab_active">
            <h2 class="help__tab-title">{{ t('help.nav_numbers') }}</h2>

            <div class="help-numbers" :class="[initialLoading && 'is-loading', busy && 'is-busy']">
              <div class="help-numbers__preloader"><span class="help-numbers__spinner"></span></div>

              <form class="help-numbers__filter" @submit.prevent="search()">
                <div class="field">
                  <label class="field__label field__label_dark" for="cdma-prefix">{{ t('help.numbers_prefix') }}</label>
                  <div class="select">
                    <select id="cdma-prefix" v-model="prefix" class="select__control">
                      <option value="">{{ t('help.numbers_all_prefixes') }}</option>
                      <option v-for="item in filters.prefixes" :key="item.value" :value="item.value">{{ item.name }}</option>
                    </select>
                    <svg class="select__chevron" viewBox="0 0 12 8" fill="none" aria-hidden="true">
                      <path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                  </div>
                </div>

                <div class="field">
                  <label class="field__label field__label_dark" for="cdma-price">{{ t('help.numbers_price') }}</label>
                  <div class="select">
                    <select id="cdma-price" v-model.number="price" class="select__control">
                      <option :value="-1">{{ t('help.numbers_all_prices') }}</option>
                      <option :value="0">{{ t('help.numbers_free') }}</option>
                      <option v-for="item in filters.prices" :key="item.code" :value="item.price">{{ spaces(item.price) }}</option>
                    </select>
                    <svg class="select__chevron" viewBox="0 0 12 8" fill="none" aria-hidden="true">
                      <path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                  </div>
                </div>

                <div class="field">
                  <label class="field__label field__label_dark" for="cdma-number">{{ t('help.numbers_number') }}</label>
                  <input
                    id="cdma-number"
                    type="text"
                    inputmode="numeric"
                    class="field__input"
                    :class="numberInvalid && 'field__input_invalid'"
                    placeholder="xx-xx"
                    maxlength="4"
                    :value="number"
                    @input="onNumber"
                  />
                </div>

                <button type="submit" class="help-numbers__submit" :aria-label="t('help.numbers_search')">
                  <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <circle cx="9" cy="9" r="6.2" stroke="currentColor" stroke-width="1.8" />
                    <path d="M14 14l4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                  </svg>
                </button>
              </form>

              <p class="help__note help-numbers__note">
                <span v-html="rich(t('help.numbers_note'), { accent: 'help-numbers__accent' })"></span>{{ ' ' }}
                <NuxtLink :to="localePath('/numbers')">{{ t('help.numbers_note_link') }}</NuxtLink>.
              </p>

              <div v-if="numbers.length" class="help-numbers__table-wrap">
                <table class="help-numbers__table">
                  <thead>
                    <tr>
                      <th>№</th>
                      <th>{{ t('help.numbers_col_number') }}</th>
                      <th>{{ t('help.numbers_col_prefix') }}</th>
                      <th>{{ t('help.numbers_col_price') }}</th>
                      <th>{{ t('help.numbers_col_date') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in numbers" :key="item.number">
                      <td>{{ (page - 1) * 15 + index + 1 }}</td>
                      <td class="help-numbers__number">{{ item.number }}</td>
                      <td>{{ item.prefix }}</td>
                      <td>{{ spaces(item.price) }}</td>
                      <td>{{ item.date }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <p v-else-if="searched && !busy" class="help-numbers__empty">{{ t('help.numbers_empty') }}</p>

              <div class="help-numbers__loader" :class="busy && 'is-active'"><span class="help-numbers__spinner"></span></div>

              <AppPagination :page="page" :pages="totalPages" @change="search($event)" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
