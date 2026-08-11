<script setup lang="ts">
const localePath = useLocalePath()
definePageMeta({ layout: 'cdma' })
useSeo({ title: "Perfectum | Акция «Скидки на номера»" })
</script>

<template>
  <!-- CDMA HERO -->
  <section class="cdma-hero cdma-hero_slim">
      <div class="container">
          <nav class="cdma-crumbs" aria-label="Хлебные крошки">
              <NuxtLink class="cdma-crumbs__link" :to="localePath('/cdma')">CDMA</NuxtLink>
              <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                      stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              <NuxtLink class="cdma-crumbs__link" :to="localePath('/cdma') + '#cdma-promo'">Акции</NuxtLink>
              <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                      stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              <span class="cdma-crumbs__current">Скидки на номера</span>
          </nav>
          <h1 class="cdma-hero__title">Скидки на номера</h1>
      </div>
  </section>

  <!-- CDMA ARTICLE -->
  <section class="cdma-detail cdma-detail_article">
      <div class="container">
          <div class="cdma-article-wrap">
              <div class="cdma-cover">
                  <span class="cdma-cover__title">SALE до 90%</span>
              </div>

              <article class="cdma-article">
                  <div class="cdma-article__meta">
                      <span class="cdma-article__icon" aria-hidden="true">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                              fill="none">
                              <path
                                  d="M3.875 8.46875H19.625M5.91071 2V3.68771M17.375 2V3.6875M17.375 3.6875H6.125C4.26104 3.6875 2.75 5.19854 2.75 7.0625V18.3126C2.75 20.1766 4.26104 21.6876 6.125 21.6876H17.375C19.239 21.6876 20.75 20.1766 20.75 18.3126L20.75 7.0625C20.75 5.19854 19.239 3.6875 17.375 3.6875ZM6.6875 12.4063H16.8125M6.6875 16.9063H16.8125"
                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                  stroke-linejoin="round" />
                          </svg>
                      </span>
                      <span class="cdma-article__date">1 декабря, 2024</span>
                      <span class="cdma-badge">Акция</span>
                      <span class="cdma-article__status">Действует</span>
                  </div>
                  <div class="cdma-article__body">
                      <p>Специальные цены на красивые номера CDMA! Скидки до 90% на золотые, серебряные и
                          бронзовые номера — выбирайте свой идеальный номер по лучшей цене.</p>
                      <p>В акции участвуют номера с префиксами <b>099, 727</b> и <b>877</b>. Скидка зависит
                          от категории номера:</p>
                      <ul>
                          <li><b>Золотые номера</b> (000, 111, 777 и т.д.) — скидка до 50%</li>
                          <li><b>Серебряные номера</b> (повторяющиеся пары, зеркальные) — скидка до 70%</li>
                          <li><b>Бронзовые номера</b> (последовательные, с одним повтором) — скидка до 90%
                          </li>
                      </ul>
                      <p>Выбрать и забронировать номер можно в разделе «Свободные номера» на странице CDMA
                          или в любом офисе обслуживания.</p>
                  </div>
              </article>

              <div class="cdma-terms">
                  <h2 class="cdma-terms__title">Условия акции</h2>
                  <ul class="cdma-terms__list">
                      <li class="cdma-terms__item">Акция действует бессрочно до отдельного уведомления.</li>
                      <li class="cdma-terms__item">Скидка применяется к разовой стоимости номера, не к
                          абонентской плате.</li>
                      <li class="cdma-terms__item">Количество номеров ограничено — по мере продажи ассортимент
                          обновляется.</li>
                      <li class="cdma-terms__item">Один абонент может приобрести не более 3 номеров по акции.
                      </li>
                      <li class="cdma-terms__item">Акция не суммируется с другими скидками и предложениями.
                      </li>
                      <li class="cdma-terms__item">Подключение номера — в офисе обслуживания или у дилера при
                          наличии паспорта.</li>
                  </ul>
              </div>

              <NuxtLink class="cdma-back" :to="localePath('/cdma') + '#cdma-promo'">
                  <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M19 12H5M11 18l-6-6 6-6" stroke="currentColor" stroke-width="1.8"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  Все акции
              </NuxtLink>
          </div>
      </div>
  </section>

  <!-- CDMA FOOTER SECTION -->
  <section class="cdma-footer">
      <div class="container">
          <nav class="cdma-footer__nav" aria-label="Разделы CDMA">
              <NuxtLink class="cdma-footer__back" aria-label="На главную" :to="localePath('/')">
                  <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M19 12H5M11 18l-6-6 6-6" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
              </NuxtLink>
              <NuxtLink class="cdma-footer__link" :to="localePath('/cdma')">CDMA</NuxtLink>
              <NuxtLink class="cdma-footer__link" :to="localePath('/cdma/dealers')">Дилеры</NuxtLink>
              <NuxtLink class="cdma-footer__link" :to="localePath('/about-company')">О Компании</NuxtLink>
          </nav>
          <span class="cdma-footer__copy">ООО «RWC» (Торговая марка Perfectum)</span>
      </div>
  </section>
</template>
