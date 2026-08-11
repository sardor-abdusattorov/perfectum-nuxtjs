<script setup lang="ts">
const localePath = useLocalePath()
useSeo({ title: "Perfectum | Новость" })
</script>

<template>
  <!-- NEWS DETAIL -->
  <section class="article">
      <div class="container">
          <div class="article__inner">
              <div class="article__topline">
                  <NuxtLink class="article__back" :to="localePath('/news')">
                      <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                          aria-hidden="true">
                          <path d="M19 12H5M11 6l-6 6 6 6" stroke="currentColor" stroke-width="1.8"
                              stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                      Назад к новостям
                  </NuxtLink>
                  <span class="article__cat article__cat_red">Компания</span>
              </div>

              <h1 class="article__title">Perfectum подвёл итоги<br>2025 года</h1>
              <p class="article__date">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                      fill="none">
                      <path
                          d="M3.875 8.46875H19.625M5.91071 2V3.68771M17.375 2V3.6875M17.375 3.6875H6.125C4.26104 3.6875 2.75 5.19854 2.75 7.0625V18.3126C2.75 20.1766 4.26104 21.6876 6.125 21.6876H17.375C19.239 21.6876 20.75 20.1766 20.75 18.3126L20.75 7.0625C20.75 5.19854 19.239 3.6875 17.375 3.6875ZM6.6875 12.4063H16.8125M6.6875 16.9063H16.8125"
                          stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                          stroke-linejoin="round" />
                  </svg>
                  02.06.2026
              </p>

              <div class="article__cover" role="img" aria-label="Иллюстрация к новости"></div>

              <div class="article__body">
                  <p>Компания Perfectum опубликовала итоги работы за 2025 год: абонентская база выросла,
                      сеть 5G Standalone расширилась на новые регионы.</p>
                  <h2 class="article__subtitle">Ключевые показатели</h2>
                  <p>Подробные финансовые показатели будут представлены в годовом отчёте компании.</p>
              </div>
          </div>
      </div>
  </section>
</template>
