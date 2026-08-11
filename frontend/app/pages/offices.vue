<script setup lang="ts">
const localePath = useLocalePath()
useSeo({ title: "Perfectum | Офисы" })
</script>

<template>
  <!-- PAGE HERO -->
  <section class="page-hero page-hero_office">
      <div class="container">
          <div class="page-hero__inner">
              <nav class="page-hero__crumbs" aria-label="Хлебные крошки">
                  <NuxtLink class="page-hero__crumb" :to="localePath('/')">Главная</NuxtLink>
                  <svg class="page-hero__crumb-sep" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                      fill="none" aria-hidden="true">
                      <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">Офисы</span>
              </nav>
              <p class="page-hero__eyebrow page-hero__eyebrow_silver">Где нас найти</p>
              <h1 class="page-hero__title section__title">Наши <span class="page-hero__title-red">офисы</span>
              </h1>
              <p class="page-hero__subtitle">Точки продаж, обслуживания и дилерская сеть Perfectum по всему
                  Узбекистану.</p>
          </div>
      </div>
  </section>

  <!-- OFFICES LOCATOR -->
  <section class="offices">
      <div class="container">
          <div class="offices__layout">
              <!-- FILTER PANEL -->
              <aside class="offices-filter" aria-label="Фильтр офисов и дилеров">
                  <div class="offices-filter__search">
                      <input type="search" class="offices-filter__search-input"
                          placeholder="Поиск по городу, адресу, названию..." aria-label="Поиск" />
                      <button type="button" class="offices-filter__search-btn" aria-label="Найти">
                          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8" />
                              <path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="1.8"
                                  stroke-linecap="round" />
                          </svg>
                      </button>
                  </div>

                  <div class="offices-filter__tabs" role="tablist" aria-label="Тип точки">
                      <button type="button" class="offices-filter__tab offices-filter__tab_active" role="tab"
                          aria-selected="true">Все</button>
                      <button type="button" class="offices-filter__tab" role="tab"
                          aria-selected="false">Офисы</button>
                      <button type="button" class="offices-filter__tab" role="tab"
                          aria-selected="false">Дилеры</button>
                  </div>

                  <div class="field">
                      <label class="field__label" for="offices-region">Регион</label>
                      <div class="select">
                          <select class="select__control" id="offices-region">
                              <option>Все регионы</option>
                              <option>Ташкент</option>
                              <option>Каракалпакстан</option>
                              <option>Хорезм</option>
                          </select>
                          <svg class="select__chevron" viewBox="0 0 12 8" fill="none"
                              xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                              <path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.6"
                                  stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                      </div>
                  </div>

                  <div class="field">
                      <label class="field__label" for="offices-city">Город/кластер</label>
                      <div class="select">
                          <select class="select__control" id="offices-city">
                              <option>Все города</option>
                              <option>Ташкент</option>
                              <option>Нукус</option>
                              <option>Ургенч</option>
                          </select>
                          <svg class="select__chevron" viewBox="0 0 12 8" fill="none"
                              xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                              <path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.6"
                                  stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                      </div>
                  </div>

                  <button type="button" class="offices-filter__locate">
                      <svg xmlns="http://www.w3.org/2000/svg" width="12" height="24" viewBox="0 0 12 24"
                          fill="none">
                          <path
                              d="M12 6C12 2.691 9.309 0 6 0C2.691 0 0 2.691 0 6C0 8.968 2.166 11.439 5 11.916V23C5 23.552 5.448 24 6 24C6.552 24 7 23.552 7 23V11.916C9.834 11.439 12 8.968 12 6Z"
                              fill="#E60000" />
                      </svg>
                      Найти ближайшие ко мне
                  </button>
                  <p class="offices-filter__hint">Местоположение не определено.</p>

                  <ul class="offices-filter__stats">
                      <li class="offices-filter__stat"><b>18</b><span>Офисов</span></li>
                      <li class="offices-filter__stat"><b>987</b><span>Дилеров</span></li>
                      <li class="offices-filter__stat"><b>1000</b><span>Показано</span></li>
                  </ul>
              </aside>

              <!-- MAP -->
              <div class="map">
                  <div class="map__canvas" id="offices-map" role="application"
                      aria-label="Карта офисов и дилеров Perfectum"></div>
                  <div class="map__zoom">
                      <button class="map__zoom-btn" type="button" data-zoom="in"
                          aria-label="Приблизить">+</button>
                      <button class="map__zoom-btn" type="button" data-zoom="out"
                          aria-label="Отдалить">−</button>
                  </div>
              </div>
          </div>

          <!-- FOUND POINTS -->
          <div class="offices__head">
              <h2 class="offices__heading">Найденные точки</h2>
              <span class="offices__result" id="offices-result">

              </span>
          </div>
          <div class="offices__list">
              <div class="offices__loader is-hidden" id="offices-loader"><span
                      class="offices__spinner"></span></div>
              <ul class="offices__grid" id="offices-list">
                  <li class="office-card" data-id="1" tabindex="0" role="button"><span
                          class="office-card__tag">офис</span>
                      <h3 class="office-card__title">Мирабадский район</h3>
                      <p class="office-card__address">улица Шевченко, 21</p>
                  </li>
                  <li class="office-card" data-id="2" tabindex="0" role="button"><span
                          class="office-card__tag">офис</span>
                      <h3 class="office-card__title">Чиланзарский район</h3>
                      <p class="office-card__address">улица Гагарина, дом 40</p>
                  </li>
                  <li class="office-card" data-id="3" tabindex="0" role="button"><span
                          class="office-card__tag">офис</span>
                      <h3 class="office-card__title">г. Андижан</h3>
                      <p class="office-card__address">проспект Бобуршох, 22Г</p>
                  </li>
                  <li class="office-card" data-id="4" tabindex="0" role="button"><span
                          class="office-card__tag">офис</span>
                      <h3 class="office-card__title">г. Бухара</h3>
                      <p class="office-card__address">улица Мустакиллик, д. 28/2, блок 4 (ориентир: к/т
                          «Бухоро»)</p>
                  </li>
                  <li class="office-card" data-id="5" tabindex="0" role="button"><span
                          class="office-card__tag">офис</span>
                      <h3 class="office-card__title">г. Карши</h3>
                      <p class="office-card__address">Шодлик МФЙ, улица Насаф</p>
                  </li>
                  <li class="office-card" data-id="6" tabindex="0" role="button"><span
                          class="office-card__tag">офис</span>
                      <h3 class="office-card__title">г. Коканд</h3>
                      <p class="office-card__address">улица Туркистон, 136Е (ориентир: Гишт куприк, рядом с
                          пожарной службой)</p>
                  </li>
                  <li class="office-card" data-id="7" tabindex="0" role="button"><span
                          class="office-card__tag">офис</span>
                      <h3 class="office-card__title">г. Фергана</h3>
                      <p class="office-card__address">улица Б. Маргилоний, Машъал МФЙ, 29</p>
                  </li>
                  <li class="office-card" data-id="8" tabindex="0" role="button"><span
                          class="office-card__tag">офис</span>
                      <h3 class="office-card__title">г. Наманган</h3>
                      <p class="office-card__address">3-й проезд Дустлик, 1</p>
                  </li>
                  <li class="office-card" data-id="9" tabindex="0" role="button"><span
                          class="office-card__tag">офис</span>
                      <h3 class="office-card__title">г. Самарканд</h3>
                      <p class="office-card__address">улица Шохрух Мирзо, 62</p>
                  </li>
                  <li class="office-card" data-id="10" tabindex="0" role="button"><span
                          class="office-card__tag">офис</span>
                      <h3 class="office-card__title">г. Термез</h3>
                      <p class="office-card__address">улица Ибн Сино, дом 29 (ориентир: ж/д вокзал, напротив
                          городского военкомата)</p>
                  </li>
                  <li class="office-card" data-id="11" tabindex="0" role="button"><span
                          class="office-card__tag">офис</span>
                      <h3 class="office-card__title">г. Навои</h3>
                      <p class="office-card__address">улица Ислама Каримова, 14А</p>
                  </li>
                  <li class="office-card" data-id="12" tabindex="0" role="button"><span
                          class="office-card__tag">офис</span>
                      <h3 class="office-card__title">г. Джизак</h3>
                      <p class="office-card__address">улица Ш. Рашидова, 300</p>
                  </li>
                  <li class="office-card" data-id="13" tabindex="0" role="button"><span
                          class="office-card__tag">офис</span>
                      <h3 class="office-card__title">г. Ургенч</h3>
                      <p class="office-card__address">улица Пахлавон Махмуд, 17</p>
                  </li>
                  <li class="office-card" data-id="14" tabindex="0" role="button"><span
                          class="office-card__tag">офис</span>
                      <h3 class="office-card__title">г. Гулистан</h3>
                      <p class="office-card__address">улица Сайхун, д. 17</p>
                  </li>
                  <li class="office-card" data-id="15" tabindex="0" role="button"><span
                          class="office-card__tag">офис</span>
                      <h3 class="office-card__title">г. Нукус</h3>
                      <p class="office-card__address">улица Каракалпакстан, д. 20</p>
                  </li>
                  <li class="office-card office-card_dealer" data-id="16" tabindex="0" role="button"><span
                          class="office-card__tag office-card__tag_dealer">Дилер</span>
                      <h3 class="office-card__title">AAA VIP MOBILE</h3>
                      <p class="office-card__address">улица М. Риёзий, дом 31, 55 квартира</p>
                  </li>
              </ul>
          </div>
          <nav class="vac-pagination" id="offices-pagination" aria-label="Страницы точек"></nav>
      </div>
  </section>
</template>
