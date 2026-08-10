<script setup lang="ts">
const localePath = useLocalePath()
</script>

<template>
  <header class="header">
      <div class="container">
          <div class="header__row">
              <div class="header__logo">
                  <NuxtLink :to="localePath('/')" class="header__logo-link">
                      <img src="/images/logo.svg" alt="Perfectum 5G" class="header__logo-img" />
                  </NuxtLink>
                  <span class="header__logo-divider" aria-hidden="true"></span>
                  <NuxtLink :to="localePath('/cdma-index')" class="header__logo-brand">CDMA</NuxtLink>
              </div>
              <nav class="header__nav">
                  <ul class="header__menu">
                      <li class="header__menu-item header__menu-item_has-submenu">
                          <a class="header__link" href="#">Мобильная связь</a>
                          <div class="header__submenu">
                              <ul class="header__submenu-list">
                                  <li class="header__submenu-item">
                                      <NuxtLink class="header__submenu-link" :to="localePath('/tariffs')">Тарифы</NuxtLink>
                                  </li>
                                  <li class="header__submenu-item">
                                      <NuxtLink class="header__submenu-link" :to="localePath('/numbers')">Свободные номера</NuxtLink>
                                  </li>
                                  <li class="header__submenu-item">
                                      <NuxtLink class="header__submenu-link" :to="localePath('/services')">Услуги</NuxtLink>
                                  </li>
                                  <li class="header__submenu-item">
                                      <NuxtLink class="header__submenu-link" :to="localePath('/device-catalog')">Каталог устройств</NuxtLink>
                                  </li>
                                  <li class="header__submenu-item">
                                      <NuxtLink class="header__submenu-link" :to="localePath('/actions')">Акции</NuxtLink>
                                  </li>
                              </ul>
                          </div>
                      </li>
                      <li class="header__menu-item">
                          <a class="header__link" href="#">Интернет</a>
                      </li>
                      <li class="header__menu-item">
                          <a class="header__link" href="#">Для Дома и офиса</a>
                      </li>
                      <li class="header__menu-item">
                          <a class="header__link" href="#">Онлайн-сервисы</a>
                      </li>
                      <li class="header__menu-item header__menu-item_has-submenu">
                          <a class="header__link" href="#">Поддержка</a>
                          <div class="header__submenu">
                              <ul class="header__submenu-list">
                                  <li class="header__submenu-item">
                                      <NuxtLink class="header__submenu-link" :to="localePath('/help')">Помощь</NuxtLink>
                                  </li>
                                  <li class="header__submenu-item">
                                      <NuxtLink class="header__submenu-link" :to="localePath('/faq')">Вопросы и ответы</NuxtLink>
                                  </li>
                                  <li class="header__submenu-item">
                                      <NuxtLink class="header__submenu-link" :to="localePath('/coverage-area')">Карта покрытия</NuxtLink>
                                  </li>
                                  <li class="header__submenu-item">
                                      <NuxtLink class="header__submenu-link" :to="localePath('/offices')">Офисы продаж</NuxtLink>
                                  </li>
                                  <li class="header__submenu-item">
                                      <NuxtLink class="header__submenu-link" :to="localePath('/documents')">Документы</NuxtLink>
                                  </li>
                                  <li class="header__submenu-item">
                                      <NuxtLink class="header__submenu-link" :to="localePath('/contacts')">Контакты</NuxtLink>
                                  </li>
                              </ul>
                          </div>
                      </li>
                      <li class="header__menu-item">
                          <NuxtLink class="header__link" :to="localePath('/news')">Новости</NuxtLink>
                      </li>
                  </ul>
              </nav>
              <div class="header__info">
                  <div class="header__lang">
                      <button type="button" class="header__lang-toggle">
                          <span class="header__lang-current">RU</span>
                          <svg viewBox="0 0 24 24" fill="none">
                              <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="1.5"
                                  stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                      </button>
                      <div class="header__lang-menu">
                          <button type="button" class="header__lang-option header__lang-option_active">
                              RU
                          </button>
                          <button type="button" class="header__lang-option">UZ</button>
                          <button type="button" class="header__lang-option">EN</button>
                      </div>
                  </div>
                  <a href="#" class="header__account">
                      <span class="header__account-icon">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                              fill="none">
                              <path
                                  d="M19.7274 20.4471C19.2716 19.1713 18.2672 18.0439 16.8701 17.2399C15.4729 16.4358 13.7611 16 12 16C10.2389 16 8.52706 16.4358 7.12991 17.2399C5.73276 18.0439 4.72839 19.1713 4.27259 20.4471"
                                  stroke="white" stroke-width="2" stroke-linecap="round" />
                              <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" />
                          </svg>
                      </span>
                      Личный кабинет
                  </a>
                  <div class="header__hamburger">
                      <button type="button" class="header__hamburger-button">
                          <span class="header__hamburger-line"></span>
                          <span class="header__hamburger-line"></span>
                          <span class="header__hamburger-line"></span>
                      </button>
                  </div>
              </div>
          </div>
      </div>
  </header>
</template>
