<script setup lang="ts">
const localePath = useLocalePath()
useSeo({ titleKey: 'seo.tariff' })
</script>

<template>
  <section class="page-hero page-hero_single_tariff">
      <div class="container">
          <div class="page-hero__inner">
              <nav class="page-hero__crumbs" aria-label="Хлебные крошки">
                  <a class="page-hero__crumb" href="#">Главная</a>
                  <svg class="page-hero__crumb-sep" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                      fill="none">
                      <path
                          d="M18 12L18.3536 12.3536L18.7071 12L18.3536 11.6464L18 12ZM6 11.5C5.72386 11.5 5.5 11.7239 5.5 12C5.5 12.2761 5.72386 12.5 6 12.5L6 12L6 11.5ZM14 16L14.3536 16.3536L18.3536 12.3536L18 12L17.6464 11.6464L13.6464 15.6464L14 16ZM18 12L18.3536 11.6464L14.3536 7.64645L14 8L13.6464 8.35355L17.6464 12.3536L18 12ZM18 12L18 11.5L6 11.5L6 12L6 12.5L18 12.5L18 12Z"
                          fill="currentColor" />
                  </svg>
                  <NuxtLink class="page-hero__crumb" :to="localePath('/tariffs')">Тарифы</NuxtLink>
                  <svg class="page-hero__crumb-sep" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                      fill="none">
                      <path
                          d="M18 12L18.3536 12.3536L18.7071 12L18.3536 11.6464L18 12ZM6 11.5C5.72386 11.5 5.5 11.7239 5.5 12C5.5 12.2761 5.72386 12.5 6 12.5L6 12L6 11.5ZM14 16L14.3536 16.3536L18.3536 12.3536L18 12L17.6464 11.6464L13.6464 15.6464L14 16ZM18 12L18.3536 11.6464L14.3536 7.64645L14 8L13.6464 8.35355L17.6464 12.3536L18 12ZM18 12L18 11.5L6 11.5L6 12L6 12.5L18 12.5L18 12Z"
                          fill="currentColor" />
                  </svg>
                  <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">A'lo 5G</span>
              </nav>
              <p class="page-hero__eyebrow page-hero__eyebrow_silver">Тариф 5G</p>
              <h1 class="page-hero__title section__title">A'LO <span class="page-hero__title-red">5G</span>
              </h1>
          </div>
      </div>
  </section>

  <section class="tariff-detail">
      <div class="container">
          <article class="tariff-detail__card">
              <div class="tariff-detail__head">
                  <span class="tariff-detail__tag">Тариф <b>5G</b></span>
              </div>
              <div class="tariff-detail__body">
                  <div class="tariff-detail__top">
                      <div class="tariff-detail__pricing">
                          <span class="tariff-detail__brand">A'LO 5G</span>
                          <p class="tariff-detail__price"><span class="tariff-detail__price-value">100
                                  000</span><span class="tariff-detail__price-currency">сум</span></p>
                          <p class="tariff-detail__price-period">в месяц · 30 дней</p>
                      </div>
                      <button type="button" class="tariff-detail__connect js-tariff-connect" data-name="A'LO 5G" data-price="100 000" data-period="/ 30 дней">Подключить</button>
                  </div>

                  <div class="tariff-detail__feats">
                      <div class="tariff-feat">
                          <span class="tariff-feat__icon">
                              <img src="/images/icon-phone.svg" alt="" />
                          </span>
                          <span class="tariff-feat__text"><b>1000 минут</b> <span
                                  class="tariff-feat__muted">(Исходящие по Узбекистану)</span></span>
                      </div>
                      <div class="tariff-feat">
                          <span class="tariff-feat__icon">
                              <img src="/images/icon-sms.svg" alt="" />
                          </span>
                          <span class="tariff-feat__text"><b>1000 SMS</b> <span class="tariff-feat__muted">(по
                                  Узбекистану)</span></span>
                      </div>
                      <div class="tariff-feat">
                          <span class="tariff-feat__icon">
                              <img src="/images/icon-globe.svg" alt="" />
                          </span>
                          <span class="tariff-feat__text"><b>1000 Гб</b></span>
                      </div>
                      <div class="tariff-feat">
                          <span class="tariff-feat__icon">
                              <img src="/images/icon-speed.svg" alt="" />
                          </span>
                          <span class="tariff-feat__text"><b>до 1 Гбит/с</b> <span
                                  class="tariff-feat__muted">(Входящая скорость интернета*)</span></span>
                      </div>
                  </div>
              </div>
          </article>

          <details class="tariff-detail__more" open>
              <summary class="tariff-detail__more-summary">
                  Подробнее
                  <svg class="tariff-detail__more-chevron" viewBox="0 0 24 24" fill="none">
                      <path d="M6 15l6-6 6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                          stroke-linejoin="round" />
                  </svg>
              </summary>
              <div class="tariff-detail__more-content">
                  <p class="tariff-detail__text">*Фактическая скорость зависит от технических условий, включая
                      зону покрытия, уровень сигнала, нагрузку сети и характеристики оборудования.</p>
                  <p class="tariff-detail__text tariff-detail__text_bold">После исчерпания интернет-трафика:
                  </p>
                  <ul class="tariff-detail__bullets">
                      <li>скорость снижается до 2 Мбит/с</li>
                      <li>для восстановления скорости можно подключить дополнительный интернет-пакет
                          (50/100/250 ГБ), либо дождаться следующего списания абонентской платы</li>
                  </ul>
                  <p class="tariff-detail__text tariff-detail__text_bold">После исчерпания минут и SMS:</p>
                  <ul class="tariff-detail__bullets">
                      <li>можно подключить дополнительный пакет минут и SMS, либо дождаться следующего
                          списания абонентской платы</li>
                  </ul>
                  <p class="tariff-detail__text">Для работы в сети необходим смартфон с поддержкой стандарта
                      5G SA и совместимых диапазонов частот с актуальной прошивкой. Список проверенных
                      устройств регулярно обновляется на нашем сайте — рекомендуем проверить совместимость
                      вашего устройства перед подключением по следующей ссылке: <a class="tariff-detail__link"
                          href="#">Совместимость устройств</a></p>
              </div>
          </details>

          <nav class="tariff-detail__switch" aria-label="Навигация по тарифам">
              <NuxtLink class="tariff-detail__switch-btn" :to="localePath('/tariffs')">Список тарифов</NuxtLink>
              <NuxtLink class="tariff-detail__switch-btn tariff-detail__switch-btn_active"
                  aria-current="page" :to="localePath('/tariffs/example')">Страница тарифа</NuxtLink>
          </nav>
      </div>
  </section>
</template>
