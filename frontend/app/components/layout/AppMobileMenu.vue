<template>
  <div class="menu">
      <div class="menu__panel">
          <div class="menu__head">
              <a href="#" class="menu__logo">
                  <img src="/images/logo-white.svg" alt="Perfectum 5G" />
              </a>
              <button type="button" class="menu__close" aria-label="Закрыть">
                  <svg viewBox="0 0 40 40" fill="none">
                      <path d="M10 30L30 10M30 30L10 10" stroke="#fff" stroke-width="2" stroke-linecap="round"
                          stroke-linejoin="round" />
                  </svg>
              </button>
          </div>
          <ul class="menu__list">
              <li class="menu__item menu__item_has-submenu">
                  <button type="button" class="menu__link menu__link_toggle">
                      Мобильная связь
                      <svg viewBox="0 0 12 8" fill="none">
                          <path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.6"
                              stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                  </button>
                  <ul class="menu__submenu">
                      <li class="menu__submenu-item">
                          <a class="menu__submenu-link" href="/tariffs">Тарифы</a>
                      </li>
                      <li class="menu__submenu-item">
                          <a class="menu__submenu-link" href="/numbers">Свободные номера</a>
                      </li>
                      <li class="menu__submenu-item">
                          <a class="menu__submenu-link" href="/services">Услуги</a>
                      </li>
                      <li class="menu__submenu-item">
                          <a class="menu__submenu-link" href="/device-catalog">Каталог устройств</a>
                      </li>
                      <li class="menu__submenu-item">
                          <a class="menu__submenu-link" href="/actions">Акции</a>
                      </li>
                  </ul>
              </li>
              <li class="menu__item">
                  <a class="menu__link" href="#">Интернет</a>
              </li>
              <li class="menu__item">
                  <a class="menu__link" href="#">Для Дома и офиса</a>
              </li>
              <li class="menu__item">
                  <a class="menu__link" href="#">Онлайн-сервисы</a>
              </li>
              <li class="menu__item menu__item_has-submenu">
                  <button type="button" class="menu__link menu__link_toggle">
                      Поддержка
                      <svg viewBox="0 0 12 8" fill="none">
                          <path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.6"
                              stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                  </button>
                  <ul class="menu__submenu">
                      <li class="menu__submenu-item">
                          <a class="menu__submenu-link" href="/help">Помощь</a>
                      </li>
                      <li class="menu__submenu-item">
                          <a class="menu__submenu-link" href="/faq">Вопросы и ответы</a>
                      </li>
                      <li class="menu__submenu-item">
                          <a class="menu__submenu-link" href="/coverage-area">Карта покрытия</a>
                      </li>
                      <li class="menu__submenu-item">
                          <a class="menu__submenu-link" href="/offices">Офисы продаж</a>
                      </li>
                      <li class="menu__submenu-item">
                          <a class="menu__submenu-link" href="/documents">Документы</a>
                      </li>
                      <li class="menu__submenu-item">
                          <a class="menu__submenu-link" href="/contacts">Контакты</a>
                      </li>
                  </ul>
              </li>
              <li class="menu__item">
                  <a class="menu__link" href="/news">Новости</a>
              </li>
          </ul>
          <a href="#" class="menu__account">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                  <path
                      d="M19.7274 20.4471C19.2716 19.1713 18.2672 18.0439 16.8701 17.2399C15.4729 16.4358 13.7611 16 12 16C10.2389 16 8.52706 16.4358 7.12991 17.2399C5.73276 18.0439 4.72839 19.1713 4.27259 20.4471"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                  <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
              </svg>
              Личный кабинет
          </a>
      </div>
  </div>
</template>
