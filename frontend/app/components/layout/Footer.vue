<script setup lang="ts">
const localePath = useLocalePath()
const socials = useSocials()
</script>

<template>
  <footer class="footer">
      <div class="container">
          <div class="footer__row">
              <div class="footer__brand">
                  <a href="#" class="footer__logo">
                      <img src="/images/logo.svg" alt="Perfectum 5G" />
                  </a>
                  <div class="footer__contacts">
                      <div class="footer__contact">
                          <h2 class="footer__contact-title">Головной офис</h2>
                          <p class="footer__contact-text">Узбекистан, Ташкент, ул. Тараса Шевченко 21</p>
                          <a class="footer__contact-link" href="#">Показать на карте</a>
                      </div>
                      <div class="footer__contact">
                          <h2 class="footer__contact-title">Телефоны поддержки</h2>
                          <ul class="footer__contact-list">
                              <li><a class="footer__contact-phone" href="#">+998 98 127 0077</a></li>
                              <li><a class="footer__contact-phone" href="#">+998 98 305 1111</a></li>
                          </ul>
                          <p class="footer__contact-text">Для абонентов Perfectum — <a
                                  class="footer__contact-phone" href="#">077</a></p>
                      </div>
                      <div class="footer__contact">
                          <h2 class="footer__contact-title">Чат с оператором в Telegram</h2>
                          <a class="footer__contact-link" href="#">@Perfectum_Support</a>
                      </div>
                      <div class="footer__contact footer__contact_email">
                          <h2 class="footer__contact-title">Email</h2>
                          <p class="footer__contact-text">Для получения информации —
                              info@perfectum.uz<br />Горячая линия для жалоб и обращений — hotline@perfectum.uz
                          </p>
                          <p class="footer__contact-text">ООО "RWC" (Торговая марка Perfectum) | Все права
                              защищены | Услуги лицензированы | Цены указаны с учетом всех налогов.</p>
                      </div>
                  </div>
              </div>

              <div class="footer__nav">
                  <div class="footer__menu">
                      <h2 class="footer__heading">Домашний интернет</h2>
                      <ul class="footer__list">
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/coverage-area')">Карта покрытия</NuxtLink>
                          </li>
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/offices')">Офисы продаж</NuxtLink>
                          </li>
                          <li class="footer__list-item"><a class="footer__list-link" href="#">Личный кабинет</a>
                          </li>
                      </ul>
                  </div>
                  <div class="footer__menu">
                      <h2 class="footer__heading">Мобильная связь</h2>
                      <ul class="footer__list">
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/tariffs')">Тарифы</NuxtLink></li>
                          <li class="footer__list-item"><a class="footer__list-link" href="#">Корпоративным
                                  клиентам</a></li>
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/services')">Услуги</NuxtLink></li>
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/numbers')">Свободные номера</NuxtLink>
                          </li>
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/devices')">Устройства</NuxtLink></li>
                          <li class="footer__list-item"><a class="footer__list-link" href="#">Сегодня в
                                  продаже</a></li>
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/coverage-area')">Зона покрытия</NuxtLink>
                          </li>
                      </ul>
                  </div>
                  <div class="footer__menu">
                      <h2 class="footer__heading">Полезное</h2>
                      <ul class="footer__list">
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/actions')">Акции</NuxtLink></li>
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/news')">Новости</NuxtLink></li>
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/offices')">Офисы</NuxtLink></li>
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/offices')">Дилеры</NuxtLink></li>
                          <li class="footer__list-item"><a class="footer__list-link" href="#">Как подключиться</a>
                          </li>
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/faq')">Полезно знать</NuxtLink>
                          </li>
                      </ul>
                  </div>
                  <div class="footer__menu">
                      <h2 class="footer__heading">Информация</h2>
                      <ul class="footer__list">
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/procurement')">Закупки</NuxtLink></li>
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/careers')">Карьера</NuxtLink></li>
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/contacts')">Контакты</NuxtLink></li>
                          <li class="footer__list-item"><a class="footer__list-link" href="#">Юридические
                                  документы</a></li>
                      </ul>
                  </div>
                  <div class="footer__widgets">
                      <div class="footer__widget">
                          <h2 class="footer__heading">Мобильное приложение</h2>
                          <div class="footer__stores">
                              <a class="store store_small" href="#">
                                  <img src="/images/google_play.svg" alt="Google Play" />
                              </a>
                              <a class="store store_small" href="#">
                                  <img src="/images/apple.svg" alt="App Store" />
                              </a>
                          </div>
                      </div>
                      <div class="footer__widget">
                          <h2 class="footer__heading">Социальные сети</h2>
                          <div class="footer__social">
                              <a
                                  v-for="social in socials"
                                  :key="social.url"
                                  class="footer__social-link"
                                  :href="social.url"
                                  target="_blank"
                                  rel="noopener"
                                  :aria-label="social.name"
                              >
                                  <Icon v-if="social.icon" :name="social.icon" size="21" />
                              </a>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </footer>
</template>
