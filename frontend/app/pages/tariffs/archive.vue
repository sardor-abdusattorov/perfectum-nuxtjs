<script setup lang="ts">
const localePath = useLocalePath()
useSeo({ titleKey: 'seo.tariffs_archive' })
</script>

<template>
  <!-- PAGE HERO -->
  <section class="page-hero page-hero_single_tariff">
      <div class="container">
          <div class="page-hero__inner">
              <nav class="page-hero__crumbs" aria-label="Хлебные крошки">
                  <NuxtLink class="page-hero__crumb" :to="localePath('/')">Главная</NuxtLink>
                  <svg class="page-hero__crumb-sep" viewBox="0 0 24 24" fill="none">
                      <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                          stroke-linejoin="round" />
                  </svg>
                  <NuxtLink class="page-hero__crumb" :to="localePath('/tariffs')">Тарифы</NuxtLink>
                  <svg class="page-hero__crumb-sep" viewBox="0 0 24 24" fill="none">
                      <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                          stroke-linejoin="round" />
                  </svg>
                  <span class="page-hero__crumb page-hero__crumb_current"
                      aria-current="page">Архив тарифов</span>
              </nav>
              <h1 class="page-hero__title section__title">Архив тарифов</h1>
          </div>
      </div>
  </section>

  <!-- ARCHIVE -->
  <section class="tariffs-archive">
      <div class="container">
          <a class="tariffs-archive__item" href="#" target="_blank" rel="noopener" download>
              <span class="tariffs-archive__doc">
                  <svg viewBox="0 0 24 24" fill="none">
                      <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8l-5-5z"
                          stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" />
                      <path d="M14 3v5h5M9 13h6M9 17h6" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
              </span>
              <span class="tariffs-archive__name">Архив тарифов 5G — 2025 (PDF)</span>
              <span class="tariffs-archive__download">
                  Скачать
                  <svg viewBox="0 0 24 24" fill="none">
                      <path d="M12 4v12m0 0l-5-5m5 5l5-5M5 20h14" stroke="currentColor" stroke-width="1.8"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
              </span>
          </a>
          <a class="tariffs-archive__item" href="#" target="_blank" rel="noopener" download>
              <span class="tariffs-archive__doc">
                  <svg viewBox="0 0 24 24" fill="none">
                      <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8l-5-5z"
                          stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" />
                      <path d="M14 3v5h5M9 13h6M9 17h6" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
              </span>
              <span class="tariffs-archive__name">Архив тарифов 5G — 2024 (PDF)</span>
              <span class="tariffs-archive__download">
                  Скачать
                  <svg viewBox="0 0 24 24" fill="none">
                      <path d="M12 4v12m0 0l-5-5m5 5l5-5M5 20h14" stroke="currentColor" stroke-width="1.8"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
              </span>
          </a>
          <a class="tariffs-archive__item" href="#" target="_blank" rel="noopener" download>
              <span class="tariffs-archive__doc">
                  <svg viewBox="0 0 24 24" fill="none">
                      <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8l-5-5z"
                          stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" />
                      <path d="M14 3v5h5M9 13h6M9 17h6" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
              </span>
              <span class="tariffs-archive__name">Тарифы «Домашний интернет» — архив 2024 (PDF)</span>
              <span class="tariffs-archive__download">
                  Скачать
                  <svg viewBox="0 0 24 24" fill="none">
                      <path d="M12 4v12m0 0l-5-5m5 5l5-5M5 20h14" stroke="currentColor" stroke-width="1.8"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
              </span>
          </a>
          <a class="tariffs-archive__item" href="#" target="_blank" rel="noopener" download>
              <span class="tariffs-archive__doc">
                  <svg viewBox="0 0 24 24" fill="none">
                      <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8l-5-5z"
                          stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" />
                      <path d="M14 3v5h5M9 13h6M9 17h6" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
              </span>
              <span class="tariffs-archive__name">Тарифы CDMA — архив 2023 (PDF)</span>
              <span class="tariffs-archive__download">
                  Скачать
                  <svg viewBox="0 0 24 24" fill="none">
                      <path d="M12 4v12m0 0l-5-5m5 5l5-5M5 20h14" stroke="currentColor" stroke-width="1.8"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
              </span>
          </a>

          <div class="tariffs-archive__footer">
              <NuxtLink class="tariffs-archive__back" :to="localePath('/tariffs')">
                  <svg viewBox="0 0 20 16" fill="none">
                      <path d="M8 1L1 8l7 7M1 8h18" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  Все тарифы
              </NuxtLink>
          </div>
      </div>
  </section>
</template>
