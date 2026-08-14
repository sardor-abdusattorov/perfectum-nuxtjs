<script setup lang="ts">
const localePath = useLocalePath()
useSeo({ titleKey: 'seo.about' })
</script>

<template>
  <!-- PAGE HERO -->
  <section class="page-hero page-hero_inner page-hero_company">
      <div class="container">
          <div class="page-hero__inner">
              <nav class="page-hero__crumbs" aria-label="Хлебные крошки">
                  <NuxtLink class="page-hero__crumb" :to="localePath('/')">Главная</NuxtLink>
                  <svg class="page-hero__crumb-sep" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                      fill="none" aria-hidden="true">
                      <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">О
                      компании</span>
              </nav>
              <p class="page-hero__eyebrow page-hero__eyebrow_silver">Компания</p>
              <h1 class="page-hero__title section__title">О <span class="page-hero__title-red">компании</span>
              </h1>
              <p class="page-hero__subtitle">Perfectum™ — первая сеть 5G Standalone в Центральной Азии.</p>
          </div>
      </div>
  </section>

  <!-- COMPANY -->
  <section class="company">
      <div class="container">
          <CompanyNav active="about" />

          <ul class="about-stats">
              <li class="about-stat">
                  <div class="about-stat__value">1995</div>
                  <div class="about-stat__label">Год основания</div>
              </li>
              <li class="about-stat">
                  <div class="about-stat__value">1-й</div>
                  <div class="about-stat__label">5G SA в центр. Азии</div>
              </li>
              <li class="about-stat">
                  <div class="about-stat__value">1 Гбит/с</div>
                  <div class="about-stat__label">Скорость 5G SA</div>
              </li>
              <li class="about-stat">
                  <div class="about-stat__value">13</div>
                  <div class="about-stat__label">Регионов присутствия</div>
              </li>
          </ul>

          <div class="about-intro">
              <p>Perfectum™ — динамично развивающаяся телекоммуникационная компания с богатой историей и
                  смелым
                  взглядом в будущее. Основанная в 1995 году, сегодня мы внедряем передовые технологии
                  мобильной
                  и фиксированной связи и формируем новый уровень телекоммуникационного рынка Узбекистана.</p>
              <p>В 2023 году Perfectum™ запустила масштабный проект «с нуля» по созданию национальной сети 5G
                  Standalone — первой полностью самостоятельной сети пятого поколения в Центральной Азии, без
                  привязки к существующей 4G-инфраструктуре.</p>
          </div>

          <ul class="timeline">
              <li class="timeline__item">
                  <div class="timeline__year">1995</div>
                  <p class="timeline__text">Основание компании, начало работы на телекоммуникационном рынке
                      Узбекистана.</p>
              </li>
              <li class="timeline__item">
                  <div class="timeline__year">2023</div>
                  <p class="timeline__text">Запуск проекта по строительству национальной сети 5G Standalone «с
                      нуля».</p>
              </li>
              <li class="timeline__item">
                  <div class="timeline__year">2024–2025</div>
                  <p class="timeline__text">Расширение зоны покрытия 5G SA на ключевые города и регионы
                      страны.</p>
              </li>
              <li class="timeline__item">
                  <div class="timeline__year">Сегодня</div>
                  <p class="timeline__text">Perfectum — современный оператор полного цикла: мобильная связь,
                      домашний интернет, CDMA.</p>
              </li>
          </ul>
      </div>
  </section>
</template>
