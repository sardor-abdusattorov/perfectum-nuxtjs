<script setup lang="ts">
const {
  filters,
  prefix,
  price,
  number,
  masked,
  numberInvalid,
  initialLoading,
  busy,
  searched,
  numbers,
  totalPages,
  page,
  search,
  onNumber,
  spaces,
} = useCdmaNumbers()

const localePath = useLocalePath()
const t = useT()
</script>

<template>
  <div class="cdma-numbers-block" :class="[initialLoading && 'is-loading', busy && 'is-busy']">
    <div class="cdma-numbers-block__preloader"><span class="cdma-numbers-block__spinner"></span></div>

    <form class="cdma-numbers" @submit.prevent="search()">
      <div class="cdma-numbers__field">
        <label class="cdma-numbers__label" for="cdma-page-prefix">{{ t('help.numbers_prefix') }}</label>
        <div class="select">
          <select id="cdma-page-prefix" v-model="prefix" class="select__control">
            <option value="">{{ t('help.numbers_all_prefixes') }}</option>
            <option v-for="item in filters.prefixes" :key="item.value" :value="item.value">{{ item.name }}</option>
          </select>
          <svg class="select__chevron" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </div>
      </div>

      <div class="cdma-numbers__field">
        <label class="cdma-numbers__label" for="cdma-page-price">{{ t('help.numbers_price') }}</label>
        <div class="select">
          <select id="cdma-page-price" v-model.number="price" class="select__control">
            <option :value="-1">{{ t('help.numbers_all_prices') }}</option>
            <option :value="0">{{ t('help.numbers_free') }}</option>
            <option v-for="item in filters.prices" :key="item.code" :value="item.price">{{ spaces(item.price) }}</option>
          </select>
          <svg class="select__chevron" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </div>
      </div>

      <div class="cdma-numbers__field cdma-numbers__field_wide">
        <label class="cdma-numbers__label" for="cdma-page-number">{{ t('help.numbers_number') }}</label>
        <div class="cdma-numbers__search">
          <input
            id="cdma-page-number"
            class="cdma-numbers__input"
            :class="numberInvalid && 'cdma-numbers__input_invalid'"
            type="text"
            inputmode="numeric"
            placeholder="xx-xx"
            maxlength="5"
            :value="masked"
            :aria-invalid="numberInvalid ? 'true' : 'false'"
            @input="onNumber"
          />
          <button class="cdma-numbers__btn" type="submit" :aria-label="t('help.numbers_search')">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8" />
              <path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
            </svg>
          </button>
        </div>
        <p v-if="numberInvalid" class="cdma-numbers__error">{{ t('help.numbers_invalid', 'Введите от 1 до 4 цифр номера') }}</p>
      </div>
    </form>

    <p class="cdma-numbers__note">
      <span v-html="rich(t('help.numbers_note'), { accent: 'cdma-numbers__accent' })"></span>{{ ' ' }}
      <NuxtLink :to="localePath('/numbers')">{{ t('help.numbers_note_link') }}</NuxtLink>.
    </p>

    <div v-if="numbers.length" class="cdma-numbers__table-wrap">
      <table class="cdma-numbers__table">
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
            <td>{{ (page - 1) * CDMA_PER_PAGE + index + 1 }}</td>
            <td class="cdma-numbers__number">{{ item.number }}</td>
            <td>{{ item.prefix }}</td>
            <td>{{ spaces(item.price) }}</td>
            <td>{{ item.date }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <p v-else-if="searched && !busy" class="cdma-numbers__empty">{{ t('help.numbers_empty') }}</p>

    <AppPagination :page="page" :pages="totalPages" @change="search($event)" />
  </div>
</template>
