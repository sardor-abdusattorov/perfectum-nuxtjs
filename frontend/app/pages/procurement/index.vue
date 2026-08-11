<script setup lang="ts">
const localePath = useLocalePath()
useSeo({ title: "Perfectum | Закупки" })
</script>

<template>
  <!-- PAGE HERO -->
  <section class="page-hero page-hero_inner page-hero_procurement">
      <div class="container">
          <div class="page-hero__inner">
              <nav class="page-hero__crumbs" aria-label="Хлебные крошки">
                  <NuxtLink class="page-hero__crumb" :to="localePath('/')">Главная</NuxtLink>
                  <svg class="page-hero__crumb-sep" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                      fill="none" aria-hidden="true">
                      <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">Закупки</span>
              </nav>
              <h1 class="page-hero__title section__title">Закупки</h1>
          </div>
      </div>
  </section>

  <!-- TENDERS -->
  <section class="tenders">
      <div class="container">
          <h2 class="tenders__sr-heading">Актуальные закупки</h2>
          <ul class="tenders__grid">
              <li class="tender-card">
                  <NuxtLink class="tender-card__link" :to="localePath('/procurement/example')">
                      <h3 class="tender-card__title">Конкурс по выбору поставщика сплит кондиционеров мощностью 18000 BTU Low…</h3>
                      <span class="tender-card__status">Открыт</span>
                  </NuxtLink>
              </li>
              <li class="tender-card">
                  <NuxtLink class="tender-card__link" :to="localePath('/procurement/example')">
                      <h3 class="tender-card__title">Конкурс на выполнение работ по ремонту и оформлению фасада офиса продаж ООО «RW…</h3>
                      <span class="tender-card__status">Открыт</span>
                  </NuxtLink>
              </li>
              <li class="tender-card">
                  <NuxtLink class="tender-card__link" :to="localePath('/procurement/example')">
                      <h3 class="tender-card__title">Конкурс на оказание услуг по размещению рекламных материалов на LED-экранах /…</h3>
                      <span class="tender-card__status">Открыт</span>
                  </NuxtLink>
              </li>
              <li class="tender-card">
                  <NuxtLink class="tender-card__link" :to="localePath('/procurement/example')">
                      <h3 class="tender-card__title">Конкурс по выбору поставщика сплит кондиционеров мощностью 18000 BTU Low…</h3>
                      <span class="tender-card__status">Открыт</span>
                  </NuxtLink>
              </li>
              <li class="tender-card">
                  <NuxtLink class="tender-card__link" :to="localePath('/procurement/example')">
                      <h3 class="tender-card__title">Конкурс на выполнение работ по ремонту и оформлению фасада офиса продаж ООО «RW…</h3>
                      <span class="tender-card__status">Открыт</span>
                  </NuxtLink>
              </li>
              <li class="tender-card">
                  <NuxtLink class="tender-card__link" :to="localePath('/procurement/example')">
                      <h3 class="tender-card__title">Конкурс на оказание услуг по размещению рекламных материалов на LED-экранах /…</h3>
                      <span class="tender-card__status">Открыт</span>
                  </NuxtLink>
              </li>
              <li class="tender-card">
                  <NuxtLink class="tender-card__link" :to="localePath('/procurement/example')">
                      <h3 class="tender-card__title">Конкурс по выбору поставщика сплит кондиционеров мощностью 18000 BTU Low…</h3>
                      <span class="tender-card__status">Открыт</span>
                  </NuxtLink>
              </li>
              <li class="tender-card">
                  <NuxtLink class="tender-card__link" :to="localePath('/procurement/example')">
                      <h3 class="tender-card__title">Конкурс на выполнение работ по ремонту и оформлению фасада офиса продаж ООО «RW…</h3>
                      <span class="tender-card__status">Открыт</span>
                  </NuxtLink>
              </li>
              <li class="tender-card">
                  <NuxtLink class="tender-card__link" :to="localePath('/procurement/example')">
                      <h3 class="tender-card__title">Конкурс на оказание услуг по размещению рекламных материалов на LED-экранах /…</h3>
                      <span class="tender-card__status">Открыт</span>
                  </NuxtLink>
              </li>
              <li class="tender-card">
                  <NuxtLink class="tender-card__link" :to="localePath('/procurement/example')">
                      <h3 class="tender-card__title">Конкурс по выбору поставщика сплит кондиционеров мощностью 18000 BTU Low…</h3>
                      <span class="tender-card__status tender-card__status_done">Завершён</span>
                  </NuxtLink>
              </li>
              <li class="tender-card">
                  <NuxtLink class="tender-card__link" :to="localePath('/procurement/example')">
                      <h3 class="tender-card__title">Конкурс на выполнение работ по ремонту и оформлению фасада офиса продаж ООО «RW…</h3>
                      <span class="tender-card__status tender-card__status_done">Завершён</span>
                  </NuxtLink>
              </li>
              <li class="tender-card">
                  <NuxtLink class="tender-card__link" :to="localePath('/procurement/example')">
                      <h3 class="tender-card__title">Конкурс на оказание услуг по размещению рекламных материалов на LED-экранах /…</h3>
                      <span class="tender-card__status tender-card__status_done">Завершён</span>
                  </NuxtLink>
              </li>
          </ul>
      </div>
  </section>
</template>
