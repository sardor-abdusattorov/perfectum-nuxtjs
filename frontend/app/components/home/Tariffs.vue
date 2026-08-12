<script setup lang="ts">
const localePath = useLocalePath()
const block = useBlock('home', 'tariffs')

const tabs = [
  { key: 'home', label: 'Дом.интернет' },
  { key: 'mobile', label: 'Мобильная связь' },
]

const chips = ['Все', 'Без покупки роутера', 'Для физических лиц', 'Для юридических лиц']

const cards = [
  { group: 'home', name: 'Asl 5G Start', label: 'Трафик интернета', price: '333 000', period: 'сум/30 дней', feats: ['Безлимит + Роутер', 'до 100 Мбит/сек'] },
  { group: 'home', name: 'Asl 5G', label: 'Трафик интернета', price: '250 000', period: 'сум/30 дней', feats: ['Безлимит', 'до 1 Гбит/сек'] },
  { group: 'home', name: 'Asl 5G Pro', label: 'Трафик интернета', price: '363 000', period: 'сум/30 дней', feats: ['Безлимит + Роутер', 'до 200 Мбит/сек'] },
  { group: 'home', name: 'Biznes 50', label: 'Трафик интернета', price: '280 000', period: 'сум/месяц', feats: ['Безлимит', 'до 50 Мбит/сек'] },
  { group: 'mobile', name: 'Mobil Start', label: 'Минуты и гигабайты', price: '45 000', period: 'сум/30 дней', feats: ['25 ГБ интернета', '500 минут'] },
  { group: 'mobile', name: 'Mobil 5G', label: 'Минуты и гигабайты', price: '75 000', period: 'сум/30 дней', feats: ['Безлимит интернет', '1000 минут'] },
  { group: 'mobile', name: 'Mobil Pro', label: 'Минуты и гигабайты', price: '110 000', period: 'сум/30 дней', feats: ['Безлимит интернет', 'Безлимит минут'] },
  { group: 'mobile', name: 'Biznes Mobil', label: 'Корпоративный', price: '130 000', period: 'сум/месяц', feats: ['Безлимит', 'до 50 SIM-карт'] },
]
</script>

<template>
  <section class="tariffs">
    <div class="container">
      <span class="section__eyebrow">{{ block.eyebrow }}</span>
      <h2 class="section__title" v-html="rich(block.title)"></h2>

      <div class="tariffs__content">
        <div class="tariffs__controls">
          <div class="tariffs__tabs">
            <button
              v-for="(tab, index) in tabs"
              :key="tab.key"
              type="button"
              class="tariffs__tab"
              :class="!index && 'tariffs__tab_active'"
              :data-tab="tab.key"
            >{{ tab.label }}</button>
          </div>
          <div class="tariffs__chips">
            <button
              v-for="(chip, index) in chips"
              :key="chip"
              type="button"
              class="tariffs__chip"
              :class="!index && 'tariffs__chip_active'"
            >{{ chip }}</button>
          </div>
        </div>

        <div class="slider-nav" aria-hidden="true">
          <button type="button" class="slider-arrow slider-arrow_prev" aria-label="Назад">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </button>
          <button type="button" class="slider-arrow slider-arrow_next" aria-label="Вперёд">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </button>
        </div>

        <div class="swiper tariffs__swiper">
          <div class="swiper-wrapper">
            <div v-for="card in cards" :key="card.name" class="swiper-slide" :data-group="card.group">
              <article class="tariffs__card">
                <div class="tariffs__card-head">
                  <h3 class="tariffs__name">{{ card.name }}</h3>
                  <div class="tariffs__label">{{ card.label }}</div>
                </div>
                <div class="tariffs__price">
                  <span class="tariffs__price-value">{{ card.price }}</span>
                  <span class="tariffs__price-period">{{ card.period }}</span>
                </div>
                <button
                  type="button"
                  class="tariffs__connect js-tariff-connect"
                  :data-name="card.name"
                  :data-price="card.price"
                  :data-period="card.period"
                >Подключить</button>
                <ul class="tariffs__feats">
                  <li v-for="feat in card.feats" :key="feat" class="tariffs__feat">{{ feat }}</li>
                </ul>
                <NuxtLink class="tariffs__more" :to="localePath('/tariffs/example')">Подробнее</NuxtLink>
              </article>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
