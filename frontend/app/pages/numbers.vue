<script setup lang="ts">
import type { ApiResponse } from '~/types/api'

interface NumberCategory {
  sku: string
  price: number
}

interface FreeNumber {
  number: string
  price: number
}

interface NumbersPayload {
  categories: NumberCategory[]
  numbers: FreeNumber[]
  page: number
  totalPages: number
}

const t = useT()
const { $api } = useNuxtApp()

useSeo({ page: 'numbers', titleKey: 'seo.numbers' })

const sku = ref('')
const page = ref(1)
const cells = ref<string[]>(Array.from({ length: 7 }, () => ''))
const inputs = useTemplateRef('inputs')
const busy = ref(false)
const failed = ref(false)

function pageSize(): number {
  return import.meta.client && window.matchMedia('(max-width: 768px)').matches ? 12 : 28
}

function mask(): string | undefined {
  const pattern = cells.value.map(cell => cell || '*').join('')

  return pattern === '*******' ? undefined : `80${pattern}`
}

const { data, refresh } = await useAsyncData<NumbersPayload | null>(
  'free-numbers',
  async () => {
    busy.value = true

    try {
      const response = await $api<ApiResponse<NumbersPayload>>('/numbers', {
        method: 'POST',
        body: { sku: sku.value, page: page.value, size: pageSize(), mask: mask() },
      })

      failed.value = false

      return response.data
    }
    catch {
      failed.value = true

      return null
    }
    finally {
      busy.value = false
    }
  },
  { default: () => null },
)

const categories = computed(() => data.value?.categories ?? [])
const numbers = computed(() => data.value?.numbers ?? [])
const totalPages = computed(() => data.value?.totalPages ?? 1)

function spaces(value: number): string {
  return String(value).replace(/\B(?=(\d{3})+(?!\d))/g, ' ')
}

function pickCategory(value: string): void {
  sku.value = value
  page.value = 1
  refresh()
}

function submitMask(): void {
  page.value = 1
  refresh()
}

function turn(step: number): void {
  const target = page.value + step

  if (target >= 1 && target <= totalPages.value && !busy.value) {
    page.value = target
    refresh()
  }
}

function onCell(index: number, event: Event): void {
  const input = event.target as HTMLInputElement
  const digit = input.value.replace(/\D/g, '').slice(0, 1)

  cells.value[index] = digit
  input.value = digit

  if (digit) {
    inputs.value?.[index + 1]?.focus()
  }
}

function onCellKeydown(index: number, event: KeyboardEvent): void {
  if (event.key === 'Backspace' && !cells.value[index]) {
    inputs.value?.[index - 1]?.focus()
  }
}
</script>

<template>
  <section class="page-hero page-hero_numbers">
    <div class="container">
      <div class="page-hero__inner">
        <p class="page-hero__eyebrow">{{ t('numbers.eyebrow') }}</p>
        <h1 class="page-hero__title section__title" v-html="rich(t('numbers.title'), { accent: 'page-hero__title-red' })"></h1>
        <p class="page-hero__subtitle">{{ t('numbers.subtitle') }}</p>
      </div>
      <span class="page-hero__watermark" aria-hidden="true">5G</span>
    </div>
  </section>

  <section class="numbers">
    <div class="container">
      <div class="numbers__body" :class="busy && 'is-busy'">
        <div v-if="categories.length" class="numbers__prices">
          <button
            type="button"
            class="numbers__price"
            :class="!sku && 'numbers__price_active'"
            @click="pickCategory('')"
          >{{ t('numbers.all') }}</button>

          <button
            v-for="category in categories"
            :key="category.sku"
            type="button"
            class="numbers__price"
            :class="category.sku === sku && 'numbers__price_active'"
            @click="pickCategory(category.sku)"
          >
            <template v-if="category.price > 0">
              {{ spaces(category.price) }} <span class="numbers__price-currency">{{ t('numbers.currency') }}</span>
            </template>
            <template v-else>{{ t('numbers.free') }}</template>
          </button>
        </div>

        <div class="numbers__controls">
          <input id="numbersMode" type="checkbox" class="numbers__mode" checked hidden />
          <label for="numbersMode" class="numbers__toggle">
            <span class="numbers__toggle-text">{{ t('numbers.toggle_select') }}</span>
            <span class="numbers__switch"></span>
            <span class="numbers__toggle-text">{{ t('numbers.toggle_mask') }}</span>
          </label>

          <form class="numbers__mask" @submit.prevent="submitMask()">
            <span class="numbers__mask-prefix">(80)</span>
            <input
              v-for="(cell, index) in cells"
              :key="index"
              ref="inputs"
              type="text"
              inputmode="numeric"
              class="numbers__mask-cell"
              maxlength="1"
              placeholder="*"
              :value="cell"
              :aria-label="t('numbers.digit')"
              @input="onCell(index, $event)"
              @keydown="onCellKeydown(index, $event)"
            />
            <button type="submit" class="numbers__submit">
              <svg viewBox="0 0 20 20" fill="none">
                <circle cx="9" cy="9" r="6.2" stroke="currentColor" stroke-width="1.8" />
                <path d="M14 14l4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
              </svg>
              {{ t('numbers.submit') }}
            </button>
          </form>
        </div>

        <div class="numbers__grid">
          <template v-if="numbers.length">
            <div v-for="item in numbers" :key="item.number" class="numbers__card">
              <span class="numbers__card-number">{{ item.number }}</span>
              <span class="numbers__card-price">
                <template v-if="item.price > 0"><b>{{ spaces(item.price) }}</b> {{ t('numbers.currency') }}</template>
                <template v-else>{{ t('numbers.free') }}</template>
              </span>
            </div>
          </template>
          <p v-else-if="!busy" class="numbers__empty">{{ t('numbers.empty') }}</p>
        </div>

        <div class="numbers__loader" :class="busy && 'is-active'"><span class="numbers__spinner"></span></div>

        <div v-if="totalPages > 1" class="numbers__pager">
          <button type="button" class="numbers__pager-btn" :disabled="page <= 1" :aria-label="t('common.prev')" @click="turn(-1)">
            <svg viewBox="0 0 8 14" fill="none"><path d="M7 1L1 7l6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" /></svg>
          </button>
          <span class="numbers__pager-current">{{ t('numbers.page') }} {{ page }}</span>
          <button type="button" class="numbers__pager-btn" :disabled="page >= totalPages" :aria-label="t('common.next')" @click="turn(1)">
            <svg viewBox="0 0 8 14" fill="none"><path d="M1 1l6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" /></svg>
          </button>
        </div>
      </div>
    </div>
  </section>
</template>
