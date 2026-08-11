<script setup lang="ts">
const localePath = useLocalePath()
definePageMeta({ layout: 'cdma' })
useSeo({ title: "Perfectum | Дилеры CDMA" })
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

  <!-- CDMA REGIONS -->
  <section class="cdma-regions">
      <div class="container">
          <ul class="cdma-regions__list">
              <li class="cdma-region-card">
                  <NuxtLink class="cdma-region-card__link" :to="localePath('/cdma/dealers/example')">
                      <h2 class="cdma-region-card__name">Ташкент</h2>
                      <span class="cdma-region-card__count">12 дилеров</span>
                      <span class="cdma-region-card__arrow" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                              <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                                  stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                      </span>
                  </NuxtLink>
              </li>
              <li class="cdma-region-card">
                  <NuxtLink class="cdma-region-card__link" :to="localePath('/cdma/dealers/example')">
                      <h2 class="cdma-region-card__name">Ташкентская область</h2>
                      <span class="cdma-region-card__count">8 дилеров</span>
                      <span class="cdma-region-card__arrow" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                              <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                                  stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                      </span>
                  </NuxtLink>
              </li>
              <li class="cdma-region-card">
                  <NuxtLink class="cdma-region-card__link" :to="localePath('/cdma/dealers/example')">
                      <h2 class="cdma-region-card__name">Респ. Каракалпакстан</h2>
                      <span class="cdma-region-card__count">5 дилеров</span>
                      <span class="cdma-region-card__arrow" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                              <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                                  stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                      </span>
                  </NuxtLink>
              </li>
              <li class="cdma-region-card">
                  <NuxtLink class="cdma-region-card__link" :to="localePath('/cdma/dealers/example')">
                      <h2 class="cdma-region-card__name">Сырдарьинская область</h2>
                      <span class="cdma-region-card__count">3 дилеров</span>
                      <span class="cdma-region-card__arrow" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                              <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                                  stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                      </span>
                  </NuxtLink>
              </li>
              <li class="cdma-region-card">
                  <NuxtLink class="cdma-region-card__link" :to="localePath('/cdma/dealers/example')">
                      <h2 class="cdma-region-card__name">Хорезмская область</h2>
                      <span class="cdma-region-card__count">4 дилеров</span>
                      <span class="cdma-region-card__arrow" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                              <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                                  stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                      </span>
                  </NuxtLink>
              </li>
              <li class="cdma-region-card">
                  <NuxtLink class="cdma-region-card__link" :to="localePath('/cdma/dealers/example')">
                      <h2 class="cdma-region-card__name">Джизакская область</h2>
                      <span class="cdma-region-card__count">3 дилеров</span>
                      <span class="cdma-region-card__arrow" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                              <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                                  stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                      </span>
                  </NuxtLink>
              </li>
              <li class="cdma-region-card">
                  <NuxtLink class="cdma-region-card__link" :to="localePath('/cdma/dealers/example')">
                      <h2 class="cdma-region-card__name">Навоийская область</h2>
                      <span class="cdma-region-card__count">4 дилеров</span>
                      <span class="cdma-region-card__arrow" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                              <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                                  stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                      </span>
                  </NuxtLink>
              </li>
              <li class="cdma-region-card">
                  <NuxtLink class="cdma-region-card__link" :to="localePath('/cdma/dealers/example')">
                      <h2 class="cdma-region-card__name">Сурхандарьинская область</h2>
                      <span class="cdma-region-card__count">3 дилеров</span>
                      <span class="cdma-region-card__arrow" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                              <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                                  stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                      </span>
                  </NuxtLink>
              </li>
              <li class="cdma-region-card">
                  <NuxtLink class="cdma-region-card__link" :to="localePath('/cdma/dealers/example')">
                      <h2 class="cdma-region-card__name">Самаркандская область</h2>
                      <span class="cdma-region-card__count">6 дилеров</span>
                      <span class="cdma-region-card__arrow" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                              <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                                  stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                      </span>
                  </NuxtLink>
              </li>
              <li class="cdma-region-card">
                  <NuxtLink class="cdma-region-card__link" :to="localePath('/cdma/dealers/example')">
                      <h2 class="cdma-region-card__name">Наманганская область</h2>
                      <span class="cdma-region-card__count">4 дилеров</span>
                      <span class="cdma-region-card__arrow" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                              <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                                  stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                      </span>
                  </NuxtLink>
              </li>
              <li class="cdma-region-card">
                  <NuxtLink class="cdma-region-card__link" :to="localePath('/cdma/dealers/example')">
                      <h2 class="cdma-region-card__name">Ферганская область</h2>
                      <span class="cdma-region-card__count">5 дилеров</span>
                      <span class="cdma-region-card__arrow" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                              <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                                  stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                      </span>
                  </NuxtLink>
              </li>
              <li class="cdma-region-card">
                  <NuxtLink class="cdma-region-card__link" :to="localePath('/cdma/dealers/example')">
                      <h2 class="cdma-region-card__name">Кашкадарьинская область</h2>
                      <span class="cdma-region-card__count">4 дилеров</span>
                      <span class="cdma-region-card__arrow" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                              <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                                  stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                      </span>
                  </NuxtLink>
              </li>
              <li class="cdma-region-card">
                  <NuxtLink class="cdma-region-card__link" :to="localePath('/cdma/dealers/example')">
                      <h2 class="cdma-region-card__name">Бухарская область</h2>
                      <span class="cdma-region-card__count">4 дилеров</span>
                      <span class="cdma-region-card__arrow" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                              <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                                  stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                      </span>
                  </NuxtLink>
              </li>
              <li class="cdma-region-card">
                  <NuxtLink class="cdma-region-card__link" :to="localePath('/cdma/dealers/example')">
                      <h2 class="cdma-region-card__name">Андижанская область</h2>
                      <span class="cdma-region-card__count">5 дилеров</span>
                      <span class="cdma-region-card__arrow" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                              <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                                  stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                      </span>
                  </NuxtLink>
              </li>
          </ul>
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
