<script setup lang="ts">
const localePath = useLocalePath()
useSeo({ title: "Perfectum | Карта покрытия" })
</script>

<template>
  <!-- PAGE HERO -->
  <section class="page-hero page-hero_inner page-hero_coverage">
      <div class="container">
          <div class="page-hero__inner">
              <nav class="page-hero__crumbs" aria-label="Хлебные крошки">
                  <NuxtLink class="page-hero__crumb" :to="localePath('/')">Главная</NuxtLink>
                  <svg class="page-hero__crumb-sep" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                      fill="none" aria-hidden="true">
                      <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">Карта
                      покрытия</span>
              </nav>
              <p class="page-hero__eyebrow page-hero__eyebrow_silver">5G Standalone</p>
              <h1 class="page-hero__title section__title">Карта<br /><span
                      class="page-hero__title-red">покрытия</span></h1>
              <p class="page-hero__subtitle">Проверьте доступность сети Perfectum 5G SA в вашем городе — в зоне
                  покрытия работает и мобильная связь, и домашний интернет.</p>
          </div>
      </div>
  </section>

  <!-- COVERAGE -->
  <section class="coverage coverage_map">
      <div class="container">
          <form class="coverage-search">
              <p class="coverage-search__label">Проверьте покрытие в вашем городе</p>
              <div class="coverage-search__controls">
                  <div class="select coverage-search__select">
                      <select class="select__control" aria-label="Город">
                          <option>Ташкент</option>
                          <option>Самарканд</option>
                          <option>Бухара</option>
                          <option>Нукус</option>
                          <option>Ургенч</option>
                      </select>
                      <svg class="select__chevron" viewBox="0 0 12 8" fill="none"
                          xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                          <path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.6"
                              stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                  </div>
                  <div class="coverage-search__find">
                      <div class="coverage-search__field">
                          <button type="button" class="coverage-search__close"
                              aria-label="Закрыть поиск по адресу">
                              <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                  aria-hidden="true">
                                  <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.8"
                                      stroke-linecap="round" />
                              </svg>
                          </button>
                          <input type="search" class="coverage-search__input"
                              placeholder="Введите адрес для поиска" aria-label="Поиск по адресу" />
                      </div>
                      <button type="submit" class="coverage-search__btn" aria-label="Проверить покрытие">
                          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                              aria-hidden="true">
                              <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8" />
                              <path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="1.8"
                                  stroke-linecap="round" />
                          </svg>
                      </button>
                  </div>
              </div>
          </form>

          <div class="map map_coverage">
              <div class="map__canvas" id="coverage-map" role="application"
                  aria-label="Карта покрытия сети Perfectum 5G по Узбекистану"></div>
              <div class="map__zoom">
                  <button class="map__zoom-btn" type="button" data-zoom="in"
                      aria-label="Приблизить">+</button>
                  <button class="map__zoom-btn" type="button" data-zoom="out"
                      aria-label="Отдалить">−</button>
              </div>
          </div>
      </div>
  </section>
</template>
