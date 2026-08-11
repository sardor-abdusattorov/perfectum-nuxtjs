<script setup lang="ts">
const localePath = useLocalePath()
definePageMeta({ layout: 'cdma' })
useSeo({ titleKey: 'seo.cdma_dealers' })
</script>

<template>
  <!-- CDMA HERO -->
  <section class="cdma-hero cdma-hero_inner">
      <div class="container">
          <nav class="cdma-crumbs" aria-label="Хлебные крошки">
              <NuxtLink class="cdma-crumbs__link" :to="localePath('/cdma')">CDMA</NuxtLink>
              <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                      stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              <span class="cdma-crumbs__current">Дилеры</span>
          </nav>
          <h1 class="cdma-hero__title">Дилеры CDMA</h1>
      </div>
  </section>

  <!-- CDMA DEALERS -->
  <section class="cdma-dealers">
      <div class="container">
          <NuxtLink class="cdma-dealers__back" :to="localePath('/cdma/dealers')">
              <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <path d="M19 12H5M11 18l-6-6 6-6" stroke="currentColor" stroke-width="1.8"
                      stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              Все регионы
          </NuxtLink>
          <h2 class="cdma-dealers__region">Ташкент</h2>
          <div class="cdma-dealers__table-wrap">
              <table class="cdma-dealers__table">
                  <thead class="cdma-dealers__head">
                      <tr class="cdma-dealers__row">
                          <th class="cdma-dealers__th" scope="col">Дилер</th>
                          <th class="cdma-dealers__th" scope="col">Адрес</th>
                          <th class="cdma-dealers__th" scope="col">Номер</th>
                      </tr>
                  </thead>
                  <tbody class="cdma-dealers__body">
                  <tr class="cdma-dealers__row cdma-dealers__row_group">
                      <th class="cdma-dealers__group" colspan="3" scope="colgroup">Мирзо-Улугбекский Р-Н</th>
                  </tr>
                  <tr class="cdma-dealers__row">
                      <td class="cdma-dealers__cell">ООО «CITY JOBS»</td>
                      <td class="cdma-dealers__cell">ул.Махорат 30 А ориентир: круг трамвая</td>
                      <td class="cdma-dealers__cell">98-313-03-30</td>
                  </tr>
                  <tr class="cdma-dealers__row">
                      <td class="cdma-dealers__cell">ООО «OPERATIV AAA»</td>
                      <td class="cdma-dealers__cell">ул.Махорат 30 А ориентир: круг трамвая</td>
                      <td class="cdma-dealers__cell">98-124-41-41</td>
                  </tr>
                  <tr class="cdma-dealers__row">
                      <td class="cdma-dealers__cell">«ZIYO NUR TA'LIM»</td>
                      <td class="cdma-dealers__cell">ул.Махорат 30 А ориентир: круг трамвая</td>
                      <td class="cdma-dealers__cell">98-363-63-63</td>
                  </tr>
                  <tr class="cdma-dealers__row">
                      <td class="cdma-dealers__cell">ЧП «ENERGY INDUSTRY STAR»</td>
                      <td class="cdma-dealers__cell">ул.Махорат 30 А ориентир: круг трамвая</td>
                      <td class="cdma-dealers__cell">98-306-66-03, 98-313-07-77</td>
                  </tr>
                  <tr class="cdma-dealers__row">
                      <td class="cdma-dealers__cell">ООО «EMPIRE DEALER NETWORK SERVICE»</td>
                      <td class="cdma-dealers__cell">ул.Махорат 30 А ориентир: круг трамвая</td>
                      <td class="cdma-dealers__cell">98-198-90-10</td>
                  </tr>
                  <tr class="cdma-dealers__row">
                      <td class="cdma-dealers__cell">ЧП «UNIVERSAL NANO TECHNO SERVICE»</td>
                      <td class="cdma-dealers__cell">ул.Махорат 30 А ориентир: круг трамвая</td>
                      <td class="cdma-dealers__cell">97-133-45-45</td>
                  </tr>
                  <tr class="cdma-dealers__row cdma-dealers__row_group">
                      <th class="cdma-dealers__group" colspan="3" scope="colgroup">Сергелийский Р-Н</th>
                  </tr>
                  <tr class="cdma-dealers__row">
                      <td class="cdma-dealers__cell">ООО «MUHABBAT MOBILE»</td>
                      <td class="cdma-dealers__cell">ул.Махорат 30 А ориентир: круг трамвая</td>
                      <td class="cdma-dealers__cell">98-127-74-75</td>
                  </tr>
                  <tr class="cdma-dealers__row cdma-dealers__row_group">
                      <th class="cdma-dealers__group" colspan="3" scope="colgroup">Учтепинский Р-Н</th>
                  </tr>
                  <tr class="cdma-dealers__row">
                      <td class="cdma-dealers__cell">ООО «GRAND DIGITAL SERVICE»</td>
                      <td class="cdma-dealers__cell">ул.Махорат 30 А ориентир: круг трамвая</td>
                      <td class="cdma-dealers__cell">98-309-40-00</td>
                  </tr>
                  <tr class="cdma-dealers__row">
                      <td class="cdma-dealers__cell">ООО «ABASA»</td>
                      <td class="cdma-dealers__cell">ул.Махорат 30 А ориентир: круг трамвая</td>
                      <td class="cdma-dealers__cell">97-774-49-79</td>
                  </tr>
                  </tbody>
              </table>
          </div>
      </div>
  </section>

  <!-- CDMA CTA -->
  <section class="cdma-cta cdma-cta_compact">
      <div class="container">
          <h2 class="cdma-cta__title">Готовы к 5G?</h2>
          <p class="cdma-cta__text">Скорости до 1 Гбит/с, VoNR-звонки, eSIM и домашний интернет без
              проводов — всё, чего нет на CDMA.</p>
          <div class="cdma-cta__actions">
              <NuxtLink class="cdma-cta__btn cdma-cta__btn_primary" :to="localePath('/')">Узнать о 5G</NuxtLink>
              <NuxtLink class="cdma-cta__btn cdma-cta__btn_ghost" :to="localePath('/coverage-area')">Проверить покрытие</NuxtLink>
          </div>
      </div>
  </section>
</template>
