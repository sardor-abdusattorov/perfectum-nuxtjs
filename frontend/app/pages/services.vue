<script setup lang="ts">
const localePath = useLocalePath()
useSeo({ titleKey: 'seo.services' })
</script>

<template>
  <!-- PAGE HERO -->
  <section class="page-hero page-hero_inner page-hero_services">
      <div class="container">
          <div class="page-hero__inner">
              <nav class="page-hero__crumbs" aria-label="Хлебные крошки">
                  <NuxtLink class="page-hero__crumb" :to="localePath('/')">Главная</NuxtLink>
                  <svg class="page-hero__crumb-sep" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                      fill="none" aria-hidden="true">
                      <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">Услуги</span>
              </nav>
              <p class="page-hero__eyebrow page-hero__eyebrow_silver">Каталог сервисов</p>
              <h1 class="page-hero__title section__title">Все <span
                      class="page-hero__title-red">услуги</span><br>в одном месте</h1>
              <p class="page-hero__subtitle">Мобильный кабинет, лояльность, дополнительные сервисы и услуги
                  для
                  абонентов домашнего интернета — выберите нужную категорию или найдите услугу по названию.
              </p>
          </div>
      </div>
  </section>

  <!-- SERVICES -->
  <section class="services">
      <div class="container">
          <div class="filter-search">
              <h2 class="filter-search__heading">Каталог услуг Perfectum</h2>
              <form class="filter-search__field" role="search" @submit.prevent>
                  <input type="search" class="filter-search__input"
                      placeholder="Найти услугу: “статический IP”, “баланс”, “роуминг”…"
                      aria-label="Поиск по услугам" />
                  <button type="submit" class="filter-search__btn" aria-label="Поиск по услугам">
                      <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                          <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8" />
                          <path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="1.8"
                              stroke-linecap="round" />
                      </svg>
                  </button>
              </form>
              <div class="filter-search__chips" role="tablist" aria-label="Категории услуг">
                  <button type="button" class="filter-search__chip filter-search__chip_active" role="tab"
                      aria-selected="true">Все <span class="filter-search__chip-count">22</span></button>
                  <button type="button" class="filter-search__chip" role="tab" aria-selected="false">Услуги
                      для домашнего интернета <span class="filter-search__chip-count">3</span></button>
                  <button type="button" class="filter-search__chip" role="tab" aria-selected="false">Сетевые
                      услуги <span class="filter-search__chip-count">3</span></button>
                  <button type="button" class="filter-search__chip" role="tab"
                      aria-selected="false">Дополнительные услуги <span
                          class="filter-search__chip-count">3</span></button>
                  <button type="button" class="filter-search__chip" role="tab" aria-selected="false">Мобильный
                      кабинет <span class="filter-search__chip-count">3</span></button>
                  <button type="button" class="filter-search__chip" role="tab" aria-selected="false">Сетевые
                      услуги <span class="filter-search__chip-count">3</span></button>
                  <button type="button" class="filter-search__chip" role="tab" aria-selected="false">Программа
                      лояльности <span class="filter-search__chip-count">3</span></button>
                  <button type="button" class="filter-search__chip" role="tab" aria-selected="false">Узнать
                      баланс <span class="filter-search__chip-count">3</span></button>
                  <button type="button" class="filter-search__chip" role="tab"
                      aria-selected="false">Абонентские услуги <span
                          class="filter-search__chip-count">3</span></button>
                  <button type="button" class="filter-search__chip" role="tab"
                      aria-selected="false">Международная связь <span
                          class="filter-search__chip-count">3</span></button>
                  <button type="button" class="filter-search__chip" role="tab" aria-selected="false">Услуги от
                      Контент-провайдеров <span class="filter-search__chip-count">3</span></button>
              </div>
          </div>

          <div class="section-head">
              <h2 class="section-head__title section-head__title_upper">Услуги для<br>домашнего интернета</h2>
              <span class="section-head__count">3 услуг</span>
          </div>

          <ul class="services__grid">
              <li class="service-card">
                  <a class="service-card__link" href="#">
                      <span class="service-card__icon">
                          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"
                              fill="none">
                              <g clip-path="url(#clip0_1242_618)">
                                  <path
                                      d="M30.4653 10.2402L29.3067 9.33356H29.3347V2.66689H26.668V7.26823L18.4653 0.849559C17.0147 -0.286441 14.9867 -0.286441 13.536 0.849559L1.53467 10.2402C0.56 11.0029 0 12.1522 0 13.3909V32.0002H12V20.0002C12 19.2656 12.5973 18.6669 13.3333 18.6669H18.6667C19.4027 18.6669 20 19.2656 20 20.0002V32.0002H32V13.3909C32 12.1522 31.44 11.0029 30.4653 10.2402ZM29.3333 29.3336H22.6667V20.0002C22.6667 17.7949 20.872 16.0002 18.6667 16.0002H13.3333C11.128 16.0002 9.33333 17.7949 9.33333 20.0002V29.3336H2.66667V13.3909C2.66667 12.9776 2.85333 12.5949 3.17867 12.3402L15.1787 2.94956C15.6627 2.57089 16.3373 2.57089 16.8213 2.94956L28.8213 12.3402C29.1467 12.5949 29.3333 12.9776 29.3333 13.3909V29.3336Z"
                                      fill="currentColor" />
                              </g>
                              <defs>
                                  <clipPath id="clip0_1242_618">
                                      <rect width="32" height="32" fill="white" />
                                  </clipPath>
                              </defs>
                          </svg>
                      </span>
                      <h3 class="service-card__title">Статический<br />IP-адрес</h3>
                      <p class="service-card__text">Постоянный внешний IP для удалённого доступа,
                          видеонаблюдения и серверов.</p>
                      <div class="service-card__foot">
                          <span class="service-card__cat">Услуги для домашнего интернета</span>
                          <span class="service-card__arrow" aria-hidden="true">
                              <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                  aria-hidden="true">
                                  <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                                      stroke-linecap="round" stroke-linejoin="round" />
                              </svg>
                          </span>
                      </div>
                  </a>
              </li>
              <li class="service-card">
                  <a class="service-card__link" href="#">
                      <span class="service-card__icon">
                          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"
                              fill="none">
                              <g clip-path="url(#clip0_1242_618)">
                                  <path
                                      d="M30.4653 10.2402L29.3067 9.33356H29.3347V2.66689H26.668V7.26823L18.4653 0.849559C17.0147 -0.286441 14.9867 -0.286441 13.536 0.849559L1.53467 10.2402C0.56 11.0029 0 12.1522 0 13.3909V32.0002H12V20.0002C12 19.2656 12.5973 18.6669 13.3333 18.6669H18.6667C19.4027 18.6669 20 19.2656 20 20.0002V32.0002H32V13.3909C32 12.1522 31.44 11.0029 30.4653 10.2402ZM29.3333 29.3336H22.6667V20.0002C22.6667 17.7949 20.872 16.0002 18.6667 16.0002H13.3333C11.128 16.0002 9.33333 17.7949 9.33333 20.0002V29.3336H2.66667V13.3909C2.66667 12.9776 2.85333 12.5949 3.17867 12.3402L15.1787 2.94956C15.6627 2.57089 16.3373 2.57089 16.8213 2.94956L28.8213 12.3402C29.1467 12.5949 29.3333 12.9776 29.3333 13.3909V29.3336Z"
                                      fill="currentColor" />
                              </g>
                              <defs>
                                  <clipPath id="clip0_1242_618">
                                      <rect width="32" height="32" fill="white" />
                                  </clipPath>
                              </defs>
                          </svg>
                      </span>
                      <h3 class="service-card__title">Расширенная гарантия<br />на оборудование</h3>
                      <p class="service-card__text">Бесплатная замена роутера или приставки при поломке не по
                          вине абонента.</p>
                      <div class="service-card__foot">
                          <span class="service-card__cat">Услуги для домашнего интернета</span>
                          <span class="service-card__arrow" aria-hidden="true">
                              <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                  aria-hidden="true">
                                  <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                                      stroke-linecap="round" stroke-linejoin="round" />
                              </svg>
                          </span>
                      </div>
                  </a>
              </li>
              <li class="service-card">
                  <a class="service-card__link" href="#">
                      <span class="service-card__icon">
                          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"
                              fill="none">
                              <g clip-path="url(#clip0_1242_618)">
                                  <path
                                      d="M30.4653 10.2402L29.3067 9.33356H29.3347V2.66689H26.668V7.26823L18.4653 0.849559C17.0147 -0.286441 14.9867 -0.286441 13.536 0.849559L1.53467 10.2402C0.56 11.0029 0 12.1522 0 13.3909V32.0002H12V20.0002C12 19.2656 12.5973 18.6669 13.3333 18.6669H18.6667C19.4027 18.6669 20 19.2656 20 20.0002V32.0002H32V13.3909C32 12.1522 31.44 11.0029 30.4653 10.2402ZM29.3333 29.3336H22.6667V20.0002C22.6667 17.7949 20.872 16.0002 18.6667 16.0002H13.3333C11.128 16.0002 9.33333 17.7949 9.33333 20.0002V29.3336H2.66667V13.3909C2.66667 12.9776 2.85333 12.5949 3.17867 12.3402L15.1787 2.94956C15.6627 2.57089 16.3373 2.57089 16.8213 2.94956L28.8213 12.3402C29.1467 12.5949 29.3333 12.9776 29.3333 13.3909V29.3336Z"
                                      fill="currentColor" />
                              </g>
                              <defs>
                                  <clipPath id="clip0_1242_618">
                                      <rect width="32" height="32" fill="white" />
                                  </clipPath>
                              </defs>
                          </svg>
                      </span>
                      <h3 class="service-card__title">Антивирус для<br />домашней сети</h3>
                      <p class="service-card__text">Защита всех устройств в сети от вредоносного трафика на
                          уровне провайдера.</p>
                      <div class="service-card__foot">
                          <span class="service-card__cat">Услуги для домашнего интернета</span>
                          <span class="service-card__arrow" aria-hidden="true">
                              <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                  aria-hidden="true">
                                  <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                                      stroke-linecap="round" stroke-linejoin="round" />
                              </svg>
                          </span>
                      </div>
                  </a>
              </li>
          </ul>

          <div class="support-cta">
              <h2 class="support-cta__title">Не нашли нужную услугу? Менеджеры поддержки помогут подобрать
                  решение под ваши задачи.</h2>
              <a href="#" class="support-cta__btn">Связаться с поддержкой</a>
          </div>
      </div>
  </section>
</template>
