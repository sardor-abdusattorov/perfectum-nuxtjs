<script setup lang="ts">
const localePath = useLocalePath()
useSeo({ titleKey: 'seo.numbers' })
</script>

<template>
  <!-- PAGE HERO -->
  <section class="page-hero page-hero_inner page-hero_help">
      <div class="container">
          <div class="page-hero__inner">
              <nav class="page-hero__crumbs" aria-label="Хлебные крошки">
                  <NuxtLink class="page-hero__crumb" :to="localePath('/')">Главная</NuxtLink>
                  <svg class="page-hero__crumb-sep" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                      fill="none" aria-hidden="true">
                      <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">FAQ</span>
              </nav>
              <h1 class="page-hero__title section__title">Частые <span
                      class="page-hero__title-red">вопросы</span></h1>
          </div>
      </div>
  </section>

  <!-- HELP HUB -->
  <section class="help">
      <div class="container">
          <div class="help__panel">
              <HelpAside active="numbers" />

              <div class="help__content">
                  <div class="help__tab help__tab_active">
                      <h2 class="help__tab-title">Свободные номера</h2>
                      <p class="help__note">Выберите красивый номер при подключении — доступны номера с
                          запоминающимися комбинациями цифр. Для резервирования обратитесь в офис продаж или
                          оставьте заявку.</p>
                      <div class="numbers-group">
                          <h3 class="numbers-group__title">Красивые комбинации</h3>
                          <ul class="numbers-group__list">
                              <li><button type="button" class="number-pill">98 777-77-77</button></li>
                              <li><button type="button" class="number-pill">98 888-88-88</button></li>
                              <li><button type="button" class="number-pill">98 111-00-11</button></li>
                              <li><button type="button" class="number-pill">98 999-00-99</button></li>
                              <li><button type="button" class="number-pill number-pill_active">98
                                      555-11-55</button></li>
                          </ul>
                      </div>
                      <div class="numbers-group">
                          <h3 class="numbers-group__title">Зеркальные</h3>
                          <ul class="numbers-group__list">
                              <li><button type="button" class="number-pill">98 123-32-10</button></li>
                              <li><button type="button" class="number-pill">98 456-65-40</button></li>
                              <li><button type="button" class="number-pill">98 321-12-30</button></li>
                              <li><button type="button" class="number-pill">98 789-98-70</button></li>
                          </ul>
                      </div>
                      <div class="numbers-group">
                          <h3 class="numbers-group__title">Повторяющиеся</h3>
                          <ul class="numbers-group__list">
                              <li><button type="button" class="number-pill">98 100-10-01</button></li>
                              <li><button type="button" class="number-pill">98 200-20-02</button></li>
                              <li><button type="button" class="number-pill">98 300-30-03</button></li>
                              <li><button type="button" class="number-pill">98 700-70-07</button></li>
                          </ul>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </section>
</template>
