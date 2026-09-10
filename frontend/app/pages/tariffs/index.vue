<script setup lang="ts">
import type { Tariff } from '~/composables/useTariffs'

const localePath = useLocalePath()
const t = useT()

const { open } = useTariffModal()

useSeo({ page: 'tariffs' })

const { data } = await useTariffCatalog()

const {
  categories,
  types,
  category,
  type,
  visible,
} = useTariffFilter(data, {
  address: true,
})

const slider = useTemplateRef('slider')

useSlider(
  slider,
  {
    slidesPerView: 'auto',
    centeredSlides: true,
    spaceBetween: 12,
    grabCursor: true,
    watchOverflow: true,

    /*
     * Swiper не должен блокировать обычные
     * клики по кнопкам и ссылкам.
     */
    preventClicks: false,
    preventClicksPropagation: false,

    navigation: {
      prevEl: '.tariffs-list .slider-arrow_prev',
      nextEl: '.tariffs-list .slider-arrow_next',
    },

    breakpoints: {
      0: {
        slidesPerView: 1.2,
        spaceBetween: 12,
        centeredSlides: false,
      },

      576: {
        slidesPerView: 1.6,
        spaceBetween: 16,
        centeredSlides: false,
      },

      992: {
        slidesPerView: 2,
        spaceBetween: 20,
        centeredSlides: false,
      },

      1200: {
        slidesPerView: 3,
        spaceBetween: 24,
        centeredSlides: false,
      },

      1700: {
        slidesPerView: 4,
        spaceBetween: 24,
        centeredSlides: false,
      },
    },
  },
  () => visible.value,
)

/**
 * Открыть подключение тарифа
 */
function connect(tariff: Tariff): void {
  open({
    name: tariff.name,
    price: tariff.price,
    price_currency: tariff.price_currency,
    price_period: tariff.price_period,
    modal_image: tariff.modal_image,
    buttons: tariff.buttons,
  })
}

/**
 * Смена категории.
 *
 * URL здесь вручную НЕ трогаем.
 * Этим должен заниматься useTariffFilter.
 */
function selectCategory(id: string): void {
  if (category.value === id)
    return

  category.value = id

  /*
   * При смене категории убираем старый type,
   * иначе может остаться фильтр от другой категории.
   */
  type.value = ''
}

/**
 * Смена типа.
 *
 * Пустая строка = "Все".
 */
function selectType(id: string): void {
  if (type.value === id)
    return

  type.value = id
}
</script>

<template>
  <section class="page-hero page-hero_tariffs">
    <div class="container">
      <div class="page-hero__inner">

        <p
          class="page-hero__eyebrow page-hero__eyebrow_silver"
        >
          {{ t('tariffs.eyebrow') }}
        </p>

        <h1
          class="page-hero__title"
          v-html="
            rich(
              t('tariffs.title'),
              {
                accent: 'page-hero__title-red',
              },
            )
          "
        />

      </div>
    </div>
  </section>

  <section class="tariffs-list">
    <div class="container">

      <!-- Категории -->
      <div
        v-if="categories.length"
        class="tariffs-list__tabs"
        role="tablist"
        :aria-label="t('tariffs.categories_label')"
      >

        <button
          v-for="item in categories"
          :key="item.id"
          type="button"
          class="tariffs-list__tab"
          :class="{
            'tariffs-list__tab_active':
              item.id === category,
          }"
          role="tab"
          :aria-selected="item.id === category"
          @click="selectCategory(item.id)"
        >
          {{ item.name }}
        </button>

      </div>

      <!-- Тип тарифа -->
      <div
        class="tariffs-list__filters"
        role="tablist"
        :aria-label="t('tariffs.types_label')"
      >

        <!-- Все -->
        <button
          type="button"
          class="tariffs-list__filter"
          :class="{
            'tariffs-list__filter_active': !type,
          }"
          role="tab"
          :aria-selected="!type"
          @click="selectType('')"
        >
          {{ t('tariffs.all') }}
        </button>

        <!-- Остальные типы -->
        <button
          v-for="item in types"
          :key="item.id"
          type="button"
          class="tariffs-list__filter"
          :class="{
            'tariffs-list__filter_active':
              item.id === type,
          }"
          role="tab"
          :aria-selected="item.id === type"
          @click="selectType(item.id)"
        >
          {{ item.name }}
        </button>

      </div>

      <!-- Стрелки -->
      <div class="slider-nav">

        <button
          type="button"
          class="slider-arrow slider-arrow_prev"
          :aria-label="t('common.prev')"
        >
          <svg
            viewBox="0 0 24 24"
            fill="none"
            aria-hidden="true"
          >
            <path
              d="M15 6l-6 6 6 6"
              stroke="currentColor"
              stroke-width="2.2"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </svg>
        </button>

        <button
          type="button"
          class="slider-arrow slider-arrow_next"
          :aria-label="t('common.next')"
        >
          <svg
            viewBox="0 0 24 24"
            fill="none"
            aria-hidden="true"
          >
            <path
              d="M9 6l6 6-6 6"
              stroke="currentColor"
              stroke-width="2.2"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </svg>
        </button>

      </div>

      <!-- Слайдер -->
      <div
        ref="slider"
        class="tariffs-list__slider swiper"
      >
        <div class="swiper-wrapper">

          <div
            v-for="tariff in visible"
            :key="tariff.slug"
            class="swiper-slide"
          >

            <article class="tariffs-list__card">

              <div class="tariffs-list__card-head">
                <span class="tariffs-list__card-tag">
                  <b>
                    {{ tariff.type?.name ?? '5G' }}
                  </b>
                </span>
              </div>

              <div class="tariffs-list__card-body">

                <div class="tariffs-list__price-row">

                  <span class="tariffs-list__brand">
                    {{ tariff.name }}
                  </span>

                  <div class="tariffs-list__price">

                    <span class="tariffs-list__price-value">
                      {{ tariff.price }}
                    </span>

                    <span class="tariffs-list__price-period">
                      {{ tariff.price_currency }}
                      /
                      {{ tariff.price_period }}
                    </span>

                  </div>
                </div>

                <TariffFeats
                  class="tariffs-list__feats"
                  :features="tariff.features"
                />

                <div class="tariffs-list__actions">

                  <button
                    type="button"
                    class="tariffs-list__connect"
                    @click="connect(tariff)"
                  >
                    {{ t('tariffs.connect') }}
                  </button>

                  <!-- Возвращаем обычный NuxtLink -->
                  <NuxtLink
                    class="tariffs-list__more"
                    :to="
                      localePath(
                        `/tariffs/${tariff.slug}`,
                      )
                    "
                  >
                    {{ t('common.read_more') }}
                  </NuxtLink>

                </div>

              </div>

            </article>

          </div>

          <div
            v-if="!visible.length"
            class="swiper-slide"
          >
            <p class="tariffs-list__empty">
              {{ t('tariffs.empty') }}
            </p>
          </div>

        </div>
      </div>

      <!-- Архив -->
      <div class="tariffs-list__footer">

        <NuxtLink
          class="tariffs-list__archive"
          :to="localePath('/tariffs/archive')"
        >
          {{ t('tariffs.archive') }}

          <svg
            viewBox="0 0 20 16"
            fill="none"
          >
            <path
              d="M12 1l7 7-7 7M19 8H1"
              stroke="currentColor"
              stroke-width="1.6"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </svg>
        </NuxtLink>

      </div>

    </div>
  </section>
</template>