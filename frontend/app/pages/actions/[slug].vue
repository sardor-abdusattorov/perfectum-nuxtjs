<script setup lang="ts">
const localePath = useLocalePath()
useSeo({ title: "Perfectum | Акция" })
</script>

<template>
  <!-- ACTION DETAIL -->
  <section class="article">
      <div class="container">
          <div class="article__inner">
              <NuxtLink class="article__back" :to="localePath('/actions')">
                  <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                      <path d="M19 12H5M11 6l-6 6 6 6" stroke="currentColor" stroke-width="1.8"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  Назад к акциям
              </NuxtLink>

              <div class="article__hero">
                  <span class="article__hero-cat">Мобильная связь</span>
                  <h1 class="article__hero-title">Бесплатное оформление<br>eSIM</h1>
              </div>

              <div class="article__card">
                  <div class="article__meta">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                          fill="none">
                          <path
                              d="M3.875 8.46875H19.625M5.91071 2V3.68771M17.375 2V3.6875M17.375 3.6875H6.125C4.26104 3.6875 2.75 5.19854 2.75 7.0625V18.3126C2.75 20.1766 4.26104 21.6876 6.125 21.6876H17.375C19.239 21.6876 20.75 20.1766 20.75 18.3126L20.75 7.0625C20.75 5.19854 19.239 3.6875 17.375 3.6875ZM6.6875 12.4063H16.8125M6.6875 16.9063H16.8125"
                              stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                              stroke-linejoin="round" />
                      </svg>
                      <span class="article__meta-date">01.06.2026 — 31.07.2026</span>
                      <span class="article__meta-status">Новая</span>
                  </div>
                  <p class="article__text">С 1 июня по 31 июля 2026 года оформление цифровой SIM-карты (eSIM)
                      при подключении любого тарифа линейки «A&#39;lo 5G» через мобильное приложение Perfectum
                      предоставляется бесплатно.</p>
                  <h2 class="article__subtitle">Условия акции</h2>
                  <ul class="article__list">
                      <li>Действует при подключении нового номера через приложение</li>
                      <li>Не суммируется с другими акциями на подключение</li>
                      <li>Доступно для устройств с поддержкой eSIM</li>
                  </ul>
              </div>
          </div>
      </div>
  </section>
</template>
