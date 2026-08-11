<script setup lang="ts">
const localePath = useLocalePath()
useSeo({ titleKey: 'seo.tariffs' })
</script>

<template>
  <section class="page-hero page-hero_tariffs">
      <div class="container">
          <div class="page-hero__inner">
              <p class="page-hero__eyebrow page-hero__eyebrow_silver">Выбери своё</p>
              <h1 class="page-hero__title">
                  Тарифы <span class="page-hero__title-red">5G</span>
              </h1>
          </div>
      </div>
  </section>

  <section class="tariffs-list">
      <div class="container">
          <div class="tariffs-list__tabs" role="tablist" aria-label="Категории услуг">
              <button type="button" class="tariffs-list__tab tariffs-list__tab_active" role="tab"
                  aria-selected="true">Мобильная связь</button>
              <button type="button" class="tariffs-list__tab" role="tab" aria-selected="false">Домашний
                  интернет</button>
              <button type="button" class="tariffs-list__tab" role="tab" aria-selected="false">CDMA</button>
          </div>

          <div class="tariffs-list__filters" role="tablist" aria-label="Типы тарифов">
              <button type="button" class="tariffs-list__filter" role="tab" aria-selected="false">Тариф
                  5G</button>
              <button type="button" class="tariffs-list__filter" role="tab" aria-selected="false">Архивные
                  тарифы</button>
              <button type="button" class="tariffs-list__filter tariffs-list__filter_active" role="tab"
                  aria-selected="true">Все</button>
          </div>

          <div class="tariffs-list__slider swiper">
              <div class="swiper-wrapper">
                  <!-- CARD -->
                  <div class="swiper-slide">
                      <article class="tariffs-list__card">
                          <div class="tariffs-list__card-head">
                              <span class="tariffs-list__card-tag">Тариф <b>5G</b></span>
                          </div>
                          <div class="tariffs-list__card-body">
                              <div class="tariffs-list__price-row">
                                  <span class="tariffs-list__brand">A'LO 5G</span>
                                  <div class="tariffs-list__price">
                                      <span class="tariffs-list__price-value">100 000</span>
                                      <span class="tariffs-list__price-period">сум / 30 дней</span>
                                  </div>
                              </div>
                              <ul class="tariffs-list__feats">
                                  <li class="tariff-feat">
                                      <span class="tariff-feat__icon">
                                          <img src="/images/icon-phone.svg" alt="" />
                                      </span>
                                      <span class="tariff-feat__text"><b>1000 минут</b> <span
                                              class="tariff-feat__muted">(Исходящие по
                                              Узбекистану)</span></span>
                                  </li>
                                  <li class="tariff-feat">
                                      <span class="tariff-feat__icon">
                                          <img src="/images/icon-sms.svg" alt="" />
                                      </span>
                                      <span class="tariff-feat__text"><b>1000 SMS</b> <span
                                              class="tariff-feat__muted">(по Узбекистану)</span></span>
                                  </li>
                                  <li class="tariff-feat">
                                      <span class="tariff-feat__icon">
                                          <img src="/images/icon-globe.svg" alt="" />
                                      </span>
                                      <span class="tariff-feat__text"><b>1000 Гб</b></span>
                                  </li>
                                  <li class="tariff-feat tariff-feat_last">
                                      <span class="tariff-feat__icon">
                                          <img src="/images/icon-speed.svg" alt="" />
                                      </span>
                                      <span class="tariff-feat__text"><b>до 1 Гбит/с</b> <span
                                              class="tariff-feat__muted">(Входящая скорость
                                              интернета*)</span></span>
                                  </li>
                              </ul>
                              <div class="tariffs-list__actions">
                                  <button type="button" class="tariffs-list__connect js-tariff-connect" data-name="Тариф 5G" data-price="100 000" data-period="/ 30 дней">Подключить</button>
                                  <NuxtLink class="tariffs-list__more" :to="localePath('/tariffs/example')">Подробнее</NuxtLink>
                              </div>
                          </div>
                      </article>
                  </div>

                  <!-- CARD -->
                  <div class="swiper-slide">
                      <article class="tariffs-list__card">
                          <div class="tariffs-list__card-head">
                              <span class="tariffs-list__card-tag">Тариф <b>5G</b></span>
                          </div>
                          <div class="tariffs-list__card-body">
                              <div class="tariffs-list__price-row">
                                  <span class="tariffs-list__brand">KELING!</span>
                                  <div class="tariffs-list__price">
                                      <span class="tariffs-list__price-value">15 000</span>
                                      <span class="tariffs-list__price-period">сум / 30 дней</span>
                                  </div>
                              </div>
                              <ul class="tariffs-list__feats">
                                  <li class="tariff-feat">
                                      <span class="tariff-feat__icon">
                                          <img src="/images/icon-phone.svg" alt="" />
                                      </span>
                                      <span class="tariff-feat__text"><b>1000 минут</b> <span
                                              class="tariff-feat__muted">(Исходящие по
                                              Узбекистану)</span></span>
                                  </li>
                                  <li class="tariff-feat">
                                      <span class="tariff-feat__icon">
                                          <img src="/images/icon-sms.svg" alt="" />
                                      </span>
                                      <span class="tariff-feat__text"><b>1000 SMS</b> <span
                                              class="tariff-feat__muted">(по Узбекистану)</span></span>
                                  </li>
                                  <li class="tariff-feat">
                                      <span class="tariff-feat__icon">
                                          <img src="/images/icon-globe.svg" alt="" />
                                      </span>
                                      <span class="tariff-feat__text"><b>1000 Гб</b></span>
                                  </li>
                                  <li class="tariff-feat tariff-feat_last">
                                      <span class="tariff-feat__icon">
                                          <img src="/images/icon-speed.svg" alt="" />
                                      </span>
                                      <span class="tariff-feat__text"><b>до 1 Гбит/с</b> <span
                                              class="tariff-feat__muted">(Входящая скорость
                                              интернета*)</span></span>
                                  </li>
                              </ul>
                              <div class="tariffs-list__actions">
                                  <button type="button" class="tariffs-list__connect js-tariff-connect" data-name="Тариф 5G" data-price="100 000" data-period="/ 30 дней">Подключить</button>
                                  <NuxtLink class="tariffs-list__more" :to="localePath('/tariffs/example')">Подробнее</NuxtLink>
                              </div>
                          </div>
                      </article>
                  </div>

                  <!-- CARD -->
                  <div class="swiper-slide">
                      <article class="tariffs-list__card">
                          <div class="tariffs-list__card-head">
                              <span class="tariffs-list__card-tag">Тариф <b>5G</b></span>
                          </div>
                          <div class="tariffs-list__card-body">
                              <div class="tariffs-list__price-row">
                                  <span class="tariffs-list__brand">A'LO 5G</span>
                                  <div class="tariffs-list__price">
                                      <span class="tariffs-list__price-value">100 000</span>
                                      <span class="tariffs-list__price-period">сум / 30 дней</span>
                                  </div>
                              </div>
                              <ul class="tariffs-list__feats">
                                  <li class="tariff-feat">
                                      <span class="tariff-feat__icon">
                                          <img src="/images/icon-phone.svg" alt="" />
                                      </span>
                                      <span class="tariff-feat__text"><b>1000 минут</b> <span
                                              class="tariff-feat__muted">(Исходящие по
                                              Узбекистану)</span></span>
                                  </li>
                                  <li class="tariff-feat">
                                      <span class="tariff-feat__icon">
                                          <img src="/images/icon-sms.svg" alt="" />
                                      </span>
                                      <span class="tariff-feat__text"><b>1000 SMS</b> <span
                                              class="tariff-feat__muted">(по Узбекистану)</span></span>
                                  </li>
                                  <li class="tariff-feat">
                                      <span class="tariff-feat__icon">
                                          <img src="/images/icon-globe.svg" alt="" />
                                      </span>
                                      <span class="tariff-feat__text"><b>1000 Гб</b></span>
                                  </li>
                                  <li class="tariff-feat tariff-feat_last">
                                      <span class="tariff-feat__icon">
                                          <img src="/images/icon-speed.svg" alt="" />
                                      </span>
                                      <span class="tariff-feat__text"><b>до 1 Гбит/с</b> <span
                                              class="tariff-feat__muted">(Входящая скорость
                                              интернета*)</span></span>
                                  </li>
                              </ul>
                              <div class="tariffs-list__actions">
                                  <button type="button" class="tariffs-list__connect js-tariff-connect" data-name="Тариф 5G" data-price="100 000" data-period="/ 30 дней">Подключить</button>
                                  <NuxtLink class="tariffs-list__more" :to="localePath('/tariffs/example')">Подробнее</NuxtLink>
                              </div>
                          </div>
                      </article>
                  </div>

                  <!-- CARD -->
                  <div class="swiper-slide">
                      <article class="tariffs-list__card">
                          <div class="tariffs-list__card-head">
                              <span class="tariffs-list__card-tag">Тариф <b>5G</b></span>
                          </div>
                          <div class="tariffs-list__card-body">
                              <div class="tariffs-list__price-row">
                                  <span class="tariffs-list__brand">A'LO 5G</span>
                                  <div class="tariffs-list__price">
                                      <span class="tariffs-list__price-value">100 000</span>
                                      <span class="tariffs-list__price-period">сум / 30 дней</span>
                                  </div>
                              </div>
                              <ul class="tariffs-list__feats">
                                  <li class="tariff-feat">
                                      <span class="tariff-feat__icon">
                                          <img src="/images/icon-phone.svg" alt="" />
                                      </span>
                                      <span class="tariff-feat__text"><b>1000 минут</b> <span
                                              class="tariff-feat__muted">(Исходящие по
                                              Узбекистану)</span></span>
                                  </li>
                                  <li class="tariff-feat">
                                      <span class="tariff-feat__icon">
                                          <img src="/images/icon-sms.svg" alt="" />
                                      </span>
                                      <span class="tariff-feat__text"><b>1000 SMS</b> <span
                                              class="tariff-feat__muted">(по Узбекистану)</span></span>
                                  </li>
                                  <li class="tariff-feat">
                                      <span class="tariff-feat__icon">
                                          <img src="/images/icon-globe.svg" alt="" />
                                      </span>
                                      <span class="tariff-feat__text"><b>1000 Гб</b></span>
                                  </li>
                                  <li class="tariff-feat tariff-feat_last">
                                      <span class="tariff-feat__icon">
                                          <img src="/images/icon-speed.svg" alt="" />
                                      </span>
                                      <span class="tariff-feat__text"><b>до 1 Гбит/с</b> <span
                                              class="tariff-feat__muted">(Входящая скорость
                                              интернета*)</span></span>
                                  </li>
                              </ul>
                              <div class="tariffs-list__actions">
                                  <button type="button" class="tariffs-list__connect js-tariff-connect" data-name="Тариф 5G" data-price="100 000" data-period="/ 30 дней">Подключить</button>
                                  <NuxtLink class="tariffs-list__more" :to="localePath('/tariffs/example')">Подробнее</NuxtLink>
                              </div>
                          </div>
                      </article>
                  </div>
              </div>
          </div>

          <div class="tariffs-list__footer">
              <NuxtLink class="tariffs-list__archive" :to="localePath('/tariffs/archive')">
                  Архивные тарифы
                  <svg viewBox="0 0 20 16" fill="none">
                      <path d="M12 1l7 7-7 7M19 8H1" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
              </NuxtLink>
          </div>
      </div>
  </section>
</template>
