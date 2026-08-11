<script setup lang="ts">
const localePath = useLocalePath()
const block = useBlock('home', 'tariffs')
const lines = (value: unknown) => String(value ?? '').split('\n')
</script>

<template>
  <section class="tariffs">
      <div class="container">
          <span class="section__eyebrow">{{ block.eyebrow }}</span>
          <h2 class="section__title">
                  <template v-for="(line, i) in lines(block.title)" :key="i">
                      <br v-if="i" />{{ line }}
                  </template>
              </h2>

          <div class="tariffs__content">
              <div class="tariffs__controls">
                  <div class="tariffs__tabs">
                      <button type="button" class="tariffs__tab tariffs__tab_active" data-tab="home">
                          Дом.интернет
                      </button>
                      <button type="button" class="tariffs__tab" data-tab="mobile">
                          Мобильная связь
                      </button>
                  </div>
                  <div class="tariffs__chips">
                      <button type="button" class="tariffs__chip tariffs__chip_active">
                          Все
                      </button>
                      <button type="button" class="tariffs__chip">
                          Без покупки роутера
                      </button>
                      <button type="button" class="tariffs__chip">
                          Для физических лиц
                      </button>
                      <button type="button" class="tariffs__chip">
                          Для юридических лиц
                      </button>
                  </div>
              </div>


              <div class="slider-nav" aria-hidden="true">
                  <button type="button" class="slider-arrow slider-arrow_prev" aria-label="Назад">
                      <svg viewBox="0 0 24 24" fill="none">
                          <path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2.2"
                              stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                  </button>
                  <button type="button" class="slider-arrow slider-arrow_next" aria-label="Вперёд">
                      <svg viewBox="0 0 24 24" fill="none">
                          <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2.2"
                              stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                  </button>
              </div>
              <div class="swiper tariffs__swiper">
                  <div class="swiper-wrapper">
                      <div class="swiper-slide" data-group="home">
                          <article class="tariffs__card">
                              <div class="tariffs__card-head">
                                  <h3 class="tariffs__name">Asl 5G Start</h3>
                                  <div class="tariffs__label">Трафик интернета</div>
                              </div>
                              <div class="tariffs__price"><span class="tariffs__price-value">333
                                      000</span><span class="tariffs__price-period">сум/30 дней</span></div>

                              <button type="button" class="tariffs__connect js-tariff-connect"
                                  data-name="Asl 5G Start" data-price="333 000" data-period="сум/30 дней">
                                  Подключить
                              </button>
                              <ul class="tariffs__feats">
                                  <li class="tariffs__feat">Безлимит + Роутер</li>
                                  <li class="tariffs__feat">до 100 Мбит/сек</li>
                              </ul>

                              <NuxtLink class="tariffs__more" :to="localePath('/tariffs/example')">Подробнее</NuxtLink>
                          </article>
                      </div>

                      <div class="swiper-slide" data-group="home">
                          <article class="tariffs__card">
                              <div class="tariffs__card-head">
                                  <h3 class="tariffs__name">Asl 5G</h3>
                                  <div class="tariffs__label">Трафик интернета</div>
                              </div>
                              <div class="tariffs__price"><span class="tariffs__price-value">250
                                      000</span><span class="tariffs__price-period">сум/30 дней</span></div>
                              <button type="button" class="tariffs__connect js-tariff-connect"
                                  data-name="Asl 5G" data-price="250 000" data-period="сум/30 дней">
                                  Подключить
                              </button>
                              <ul class="tariffs__feats">
                                  <li class="tariffs__feat">Безлимит</li>
                                  <li class="tariffs__feat">до 1 Гбит/сек</li>
                              </ul>
                              <NuxtLink class="tariffs__more" :to="localePath('/tariffs/example')">Подробнее</NuxtLink>
                          </article>
                      </div>

                      <div class="swiper-slide" data-group="home">
                          <article class="tariffs__card">
                              <div class="tariffs__card-head">
                                  <h3 class="tariffs__name">Asl 5G Pro</h3>
                                  <div class="tariffs__label">Трафик интернета</div>
                              </div>
                              <div class="tariffs__price"><span class="tariffs__price-value">363
                                      000</span><span class="tariffs__price-period">сум/30 дней</span></div>
                              <button type="button" class="tariffs__connect js-tariff-connect"
                                  data-name="Asl 5G Pro" data-price="363 000" data-period="сум/30 дней">
                                  Подключить
                              </button>
                              <ul class="tariffs__feats">
                                  <li class="tariffs__feat">Безлимит + Роутер</li>
                                  <li class="tariffs__feat">до 200 Мбит/сек</li>
                              </ul>
                              <NuxtLink class="tariffs__more" :to="localePath('/tariffs/example')">Подробнее</NuxtLink>
                          </article>
                      </div>

                      <div class="swiper-slide" data-group="home">
                          <article class="tariffs__card">
                              <div class="tariffs__card-head">
                                  <h3 class="tariffs__name">Biznes 50</h3>
                                  <div class="tariffs__label">Трафик интернета</div>
                              </div>
                              <div class="tariffs__price"><span class="tariffs__price-value">280
                                      000</span><span class="tariffs__price-period">сум/месяц</span></div>
                              <button type="button" class="tariffs__connect js-tariff-connect"
                                  data-name="Biznes 50" data-price="280 000" data-period="сум/месяц">
                                  Подключить
                              </button>
                              <ul class="tariffs__feats">
                                  <li class="tariffs__feat">Безлимит</li>
                                  <li class="tariffs__feat">до 50 Мбит/сек</li>
                              </ul>
                              <NuxtLink class="tariffs__more" :to="localePath('/tariffs/example')">Подробнее</NuxtLink>
                          </article>
                      </div>

                      <div class="swiper-slide" data-group="mobile">
                          <article class="tariffs__card">
                              <div class="tariffs__card-head">
                                  <h3 class="tariffs__name">Mobil Start</h3>
                                  <div class="tariffs__label">Минуты и гигабайты</div>
                              </div>
                              <div class="tariffs__price"><span class="tariffs__price-value">45
                                      000</span><span class="tariffs__price-period">сум/30 дней</span></div>
                              <button type="button" class="tariffs__connect js-tariff-connect"
                                  data-name="Mobil Start" data-price="45 000" data-period="сум/30 дней">
                                  Подключить
                              </button>
                              <ul class="tariffs__feats">
                                  <li class="tariffs__feat">25 ГБ интернета</li>
                                  <li class="tariffs__feat">500 минут</li>
                              </ul>
                              <NuxtLink class="tariffs__more" :to="localePath('/tariffs/example')">Подробнее</NuxtLink>
                          </article>
                      </div>

                      <div class="swiper-slide" data-group="mobile">
                          <article class="tariffs__card">
                              <div class="tariffs__card-head">
                                  <h3 class="tariffs__name">Mobil 5G</h3>
                                  <div class="tariffs__label">Минуты и гигабайты</div>
                              </div>
                              <div class="tariffs__price"><span class="tariffs__price-value">75
                                      000</span><span class="tariffs__price-period">сум/30 дней</span></div>
                              <button type="button" class="tariffs__connect js-tariff-connect"
                                  data-name="Mobil 5G" data-price="75 000" data-period="сум/30 дней">
                                  Подключить
                              </button>
                              <ul class="tariffs__feats">
                                  <li class="tariffs__feat">Безлимит интернет</li>
                                  <li class="tariffs__feat">1000 минут</li>
                              </ul>
                              <NuxtLink class="tariffs__more" :to="localePath('/tariffs/example')">Подробнее</NuxtLink>
                          </article>
                      </div>

                      <div class="swiper-slide" data-group="mobile">
                          <article class="tariffs__card">
                              <div class="tariffs__card-head">
                                  <h3 class="tariffs__name">Mobil Pro</h3>
                                  <div class="tariffs__label">Минуты и гигабайты</div>
                              </div>
                              <div class="tariffs__price"><span class="tariffs__price-value">110
                                      000</span><span class="tariffs__price-period">сум/30 дней</span></div>
                              <button type="button" class="tariffs__connect js-tariff-connect"
                                  data-name="Mobil Pro" data-price="110 000" data-period="сум/30 дней">
                                  Подключить
                              </button>
                              <ul class="tariffs__feats">
                                  <li class="tariffs__feat">Безлимит интернет</li>
                                  <li class="tariffs__feat">Безлимит минут</li>
                              </ul>
                              <NuxtLink class="tariffs__more" :to="localePath('/tariffs/example')">Подробнее</NuxtLink>
                          </article>
                      </div>

                      <div class="swiper-slide" data-group="mobile">
                          <article class="tariffs__card">
                              <div class="tariffs__card-head">
                                  <h3 class="tariffs__name">Biznes Mobil</h3>
                                  <div class="tariffs__label">Корпоративный</div>
                              </div>
                              <div class="tariffs__price"><span class="tariffs__price-value">130
                                      000</span><span class="tariffs__price-period">сум/месяц</span></div>
                              <button type="button" class="tariffs__connect js-tariff-connect"
                                  data-name="Biznes Mobil" data-price="130 000" data-period="сум/месяц">
                                  Подключить
                              </button>
                              <ul class="tariffs__feats">
                                  <li class="tariffs__feat">Безлимит</li>
                                  <li class="tariffs__feat">до 50 SIM-карт</li>
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
