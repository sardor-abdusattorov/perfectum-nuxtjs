<script setup lang="ts">
const localePath = useLocalePath()
useSeo({ titleKey: 'seo.actions' })
</script>

<template>
  <!-- PAGE HERO -->
  <section class="page-hero page-hero_inner page-hero_actions">
      <div class="container">
          <div class="page-hero__inner">
              <nav class="page-hero__crumbs" aria-label="Хлебные крошки">
                  <NuxtLink class="page-hero__crumb" :to="localePath('/')">Главная</NuxtLink>
                  <svg class="page-hero__crumb-sep" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                      fill="none" aria-hidden="true">
                      <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">Акции</span>
              </nav>
              <h1 class="page-hero__title section__title">Акции</h1>
          </div>
      </div>
  </section>

  <!-- ACTIONS -->
  <section class="actions">
      <div class="container">
          <div class="filter-search">
              <div class="filter-search__field">
                  <input type="search" class="filter-search__input" placeholder="Поиск по акциям…"
                      aria-label="Поиск по акциям" />
                  <svg class="filter-search__btn" viewBox="0 0 24 24" fill="none"
                      xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                      <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8" />
                      <path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="1.8"
                          stroke-linecap="round" />
                  </svg>
              </div>
              <div class="filter-search__chips" role="tablist" aria-label="Категории акций">
                  <button type="button" class="filter-search__chip filter-search__chip_active" role="tab" aria-selected="true">Все <span class="filter-search__chip-count">22</span></button>
                  <button type="button" class="filter-search__chip" role="tab" aria-selected="false">Мобильная связь <span class="filter-search__chip-count">3</span></button>
                  <button type="button" class="filter-search__chip" role="tab" aria-selected="false">Домашний интернет <span class="filter-search__chip-count">3</span></button>
                  <button type="button" class="filter-search__chip" role="tab" aria-selected="false">Устройства <span class="filter-search__chip-count">3</span></button>
              </div>
          </div>

          <div class="section-head">
              <h2 class="section-head__title">Все акции</h2>
              <span class="section-head__count">6 акций</span>
          </div>

          <ul class="promo-grid">
              <li class="promo-card">
                  <NuxtLink class="promo-card__link" :to="localePath('/actions/example')">
                      <div class="promo-card__media">
                          <span class="promo-card__badge">Новая</span>
                          <span class="promo-card__cat">Мобильная связь</span>
                      </div>
                      <div class="promo-card__body">
                          <h3 class="promo-card__title">Бесплатное оформление eSIM</h3>
                          <p class="promo-card__text">При подключении тарифа «A&#39;lo 5G» оформление eSIM в приложении бесплатно — без визита в офис.</p>
                          <div class="promo-card__foot">
                              <span class="promo-card__date">до 31.07.2026</span>
                              <span class="promo-card__arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg></span>
                          </div>
                      </div>
                  </NuxtLink>
              </li>
              <li class="promo-card promo-card_active">
                  <NuxtLink class="promo-card__link" :to="localePath('/actions/example')">
                      <div class="promo-card__media">
                          <span></span>
                          <span class="promo-card__cat">Мобильная связь</span>
                      </div>
                      <div class="promo-card__body">
                          <h3 class="promo-card__title">Скидка 20% на семейные тарифы</h3>
                          <p class="promo-card__text">Подключите второй и третий номер на тариф «Keling! Plus» со скидкой 20% к ежемесячному платежу.</p>
                          <div class="promo-card__foot">
                              <span class="promo-card__date">до 31.07.2026</span>
                              <span class="promo-card__arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg></span>
                          </div>
                      </div>
                  </NuxtLink>
              </li>
              <li class="promo-card">
                  <NuxtLink class="promo-card__link" :to="localePath('/actions/example')">
                      <div class="promo-card__media">
                          <span class="promo-card__badge">Хит</span>
                          <span class="promo-card__cat">Домашний интернет</span>
                      </div>
                      <div class="promo-card__body">
                          <h3 class="promo-card__title">Бесплатная установка роутера</h3>
                          <p class="promo-card__text">При подключении домашнего интернета 5G SA — установка и настройка CPE-роутера бесплатно.</p>
                          <div class="promo-card__foot">
                              <span class="promo-card__date">до 31.07.2026</span>
                              <span class="promo-card__arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg></span>
                          </div>
                      </div>
                  </NuxtLink>
              </li>
              <li class="promo-card">
                  <NuxtLink class="promo-card__link" :to="localePath('/actions/example')">
                      <div class="promo-card__media">
                          <span class="promo-card__badge">Новая</span>
                          <span class="promo-card__cat">Мобильная связь</span>
                      </div>
                      <div class="promo-card__body">
                          <h3 class="promo-card__title">Бесплатное оформление eSIM</h3>
                          <p class="promo-card__text">При подключении тарифа «A&#39;lo 5G» оформление eSIM в приложении бесплатно — без визита в офис.</p>
                          <div class="promo-card__foot">
                              <span class="promo-card__date">до 31.07.2026</span>
                              <span class="promo-card__arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg></span>
                          </div>
                      </div>
                  </NuxtLink>
              </li>
              <li class="promo-card">
                  <NuxtLink class="promo-card__link" :to="localePath('/actions/example')">
                      <div class="promo-card__media">
                          <span></span>
                          <span class="promo-card__cat">Мобильная связь</span>
                      </div>
                      <div class="promo-card__body">
                          <h3 class="promo-card__title">Скидка 20% на семейные тарифы</h3>
                          <p class="promo-card__text">Подключите второй и третий номер на тариф «Keling! Plus» со скидкой 20% к ежемесячному платежу.</p>
                          <div class="promo-card__foot">
                              <span class="promo-card__date">до 31.07.2026</span>
                              <span class="promo-card__arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg></span>
                          </div>
                      </div>
                  </NuxtLink>
              </li>
              <li class="promo-card">
                  <NuxtLink class="promo-card__link" :to="localePath('/actions/example')">
                      <div class="promo-card__media">
                          <span class="promo-card__badge">Хит</span>
                          <span class="promo-card__cat">Домашний интернет</span>
                      </div>
                      <div class="promo-card__body">
                          <h3 class="promo-card__title">Бесплатная установка роутера</h3>
                          <p class="promo-card__text">При подключении домашнего интернета 5G SA — установка и настройка CPE-роутера бесплатно.</p>
                          <div class="promo-card__foot">
                              <span class="promo-card__date">до 31.07.2026</span>
                              <span class="promo-card__arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg></span>
                          </div>
                      </div>
                  </NuxtLink>
              </li>
          </ul>

          <div class="section-foot">
              <a href="#" class="btn-pill-outline">Архив акций
                  <svg viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                      <path d="M12 1l7 7-7 7M19 8H1" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
              </a>
          </div>
      </div>
  </section>
</template>
