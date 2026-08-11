<script setup lang="ts">
const localePath = useLocalePath()
useSeo({ titleKey: 'seo.news' })
</script>

<template>
  <!-- PAGE HERO -->
  <section class="page-hero page-hero_inner page-hero_news">
      <div class="container">
          <div class="page-hero__inner">
              <nav class="page-hero__crumbs" aria-label="Хлебные крошки">
                  <NuxtLink class="page-hero__crumb" :to="localePath('/')">Главная</NuxtLink>
                  <svg class="page-hero__crumb-sep" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                      fill="none" aria-hidden="true">
                      <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">Новости</span>
              </nav>
              <h1 class="page-hero__title section__title">Новости <span
                      class="page-hero__title-red">Perfectum</span></h1>
          </div>
      </div>
  </section>

  <!-- NEWS -->
  <section class="news">
      <div class="container">
          <div class="filter-search">
              <div class="filter-search__field">
                  <input type="search" class="filter-search__input" placeholder="Поиск по новостям…"
                      aria-label="Поиск по новостям" />
                  <svg class="filter-search__btn" viewBox="0 0 24 24" fill="none"
                      xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                      <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8" />
                      <path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="1.8"
                          stroke-linecap="round" />
                  </svg>
              </div>
              <div class="filter-search__chips" role="tablist" aria-label="Категории новостей">
                  <button type="button" class="filter-search__chip filter-search__chip_active" role="tab" aria-selected="true">Все <span class="filter-search__chip-count">22</span></button>
                  <button type="button" class="filter-search__chip" role="tab" aria-selected="false">Развитие сети <span class="filter-search__chip-count">3</span></button>
                  <button type="button" class="filter-search__chip" role="tab" aria-selected="false">Компания <span class="filter-search__chip-count">3</span></button>
                  <button type="button" class="filter-search__chip" role="tab" aria-selected="false">Продукты и услуги <span class="filter-search__chip-count">3</span></button>
              </div>
          </div>

          <article class="news-feature">
              <div class="news-feature__media">
                  <span class="news-feature__cat">Развитие сети</span>
                  <h2 class="news-feature__title">Perfectum запустил 5G Standalone в Андижане</h2>
              </div>
              <div class="news-feature__body">
                  <span class="news-feature__date">20.06.2026</span>
                  <p class="news-feature__text">Сеть пятого поколения стала доступна жителям Андижана — это уже
                      пятый город с покрытием 5G SA.</p>
                  <NuxtLink class="news-feature__link" :to="localePath('/news/example')">Читать полностью <svg viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M12 1l7 7-7 7M19 8H1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg></NuxtLink>
              </div>
          </article>

          <div class="section-head">
              <h2 class="section-head__title">Все новости</h2>
              <span class="section-head__count">6 новостей</span>
          </div>

          <ul class="news-grid">
              <li class="news-card">
                  <NuxtLink class="news-card__link" :to="localePath('/news/example')">
                      <div class="news-card__media">
                          <span class="news-card__cat">Продукты и услуги</span>
                      </div>
                      <div class="news-card__body">
                          <h3 class="news-card__title">В приложении Perfectum появилось оформление eSIM</h3>
                          <p class="news-card__text">Теперь цифровую SIM-карту можно оформить полностью онлайн — без визита в офис продаж.</p>
                          <div class="news-card__foot"><span class="news-card__date">до 31.07.2026</span><span class="news-card__more">Читать полностью<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span></div>
                      </div>
                  </NuxtLink>
              </li>
              <li class="news-card">
                  <NuxtLink class="news-card__link" :to="localePath('/news/example')">
                      <div class="news-card__media">
                          <span class="news-card__cat">Компания</span>
                      </div>
                      <div class="news-card__body">
                          <h3 class="news-card__title">Perfectum подвёл итоги 2025 года</h3>
                          <p class="news-card__text">Рост абонентской базы, расширение сети и запуск новых тарифов — главные итоги прошедшего года.</p>
                          <div class="news-card__foot"><span class="news-card__date">до 31.07.2026</span><span class="news-card__more">Читать полностью<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span></div>
                      </div>
                  </NuxtLink>
              </li>
              <li class="news-card">
                  <NuxtLink class="news-card__link" :to="localePath('/news/example')">
                      <div class="news-card__media">
                          <span class="news-card__cat">Продукты и услуги</span>
                      </div>
                      <div class="news-card__body">
                          <h3 class="news-card__title">Новый тариф «A&#39;lo 5G Max» с безлимитным интернетом</h3>
                          <p class="news-card__text">Безлимитный мобильный интернет на скорости 5G SA — для тех, кто много работает онлайн.</p>
                          <div class="news-card__foot"><span class="news-card__date">до 31.07.2026</span><span class="news-card__more">Читать полностью<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span></div>
                      </div>
                  </NuxtLink>
              </li>
              <li class="news-card">
                  <NuxtLink class="news-card__link" :to="localePath('/news/example')">
                      <div class="news-card__media">
                          <span class="news-card__cat">Продукты и услуги</span>
                      </div>
                      <div class="news-card__body">
                          <h3 class="news-card__title">В приложении Perfectum появилось оформление eSIM</h3>
                          <p class="news-card__text">Теперь цифровую SIM-карту можно оформить полностью онлайн — без визита в офис продаж.</p>
                          <div class="news-card__foot"><span class="news-card__date">до 31.07.2026</span><span class="news-card__more">Читать полностью<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span></div>
                      </div>
                  </NuxtLink>
              </li>
              <li class="news-card">
                  <NuxtLink class="news-card__link" :to="localePath('/news/example')">
                      <div class="news-card__media">
                          <span class="news-card__cat">Компания</span>
                      </div>
                      <div class="news-card__body">
                          <h3 class="news-card__title">Perfectum подвёл итоги 2025 года</h3>
                          <p class="news-card__text">Рост абонентской базы, расширение сети и запуск новых тарифов — главные итоги прошедшего года.</p>
                          <div class="news-card__foot"><span class="news-card__date">до 31.07.2026</span><span class="news-card__more">Читать полностью<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span></div>
                      </div>
                  </NuxtLink>
              </li>
              <li class="news-card">
                  <NuxtLink class="news-card__link" :to="localePath('/news/example')">
                      <div class="news-card__media">
                          <span class="news-card__cat">Продукты и услуги</span>
                      </div>
                      <div class="news-card__body">
                          <h3 class="news-card__title">Новый тариф «A&#39;lo 5G Max» с безлимитным интернетом</h3>
                          <p class="news-card__text">Безлимитный мобильный интернет на скорости 5G SA — для тех, кто много работает онлайн.</p>
                          <div class="news-card__foot"><span class="news-card__date">до 31.07.2026</span><span class="news-card__more">Читать полностью<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span></div>
                      </div>
                  </NuxtLink>
              </li>
          </ul>

          <nav class="pagination" aria-label="Пагинация">
              <a class="pagination__arrow" href="#" aria-label="Назад">
                  <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
              </a>
              <div class="pagination__pages">
                  <a class="pagination__page" href="#">1</a>
                  <a class="pagination__page pagination__page_active" href="#" aria-current="page">2</a>
                  <a class="pagination__page" href="#">3</a>
                  <a class="pagination__page" href="#">4</a>
                  <a class="pagination__page" href="#">5</a>
                  <a class="pagination__page" href="#">6</a>
                  <span class="pagination__page">…</span>
              </div>
              <a class="pagination__arrow" href="#" aria-label="Вперёд">
                  <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
              </a>
          </nav>
      </div>
  </section>
</template>
