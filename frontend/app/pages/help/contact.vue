<script setup lang="ts">
const localePath = useLocalePath()
useSeo({ titleKey: 'seo.help_contact' })
</script>

<template>
  <!-- PAGE HERO -->
  <section class="page-hero page-hero_inner page-hero_help">
      <div class="container">
          <div class="page-hero__inner">
              <nav class="page-hero__crumbs" aria-label="Хлебные крошки">
                  <NuxtLink class="page-hero__crumb" :to="localePath('/')">Главная</NuxtLink>
                  <svg class="page-hero__crumb-sep" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                      fill="none" aria-hidden="true">
                      <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">FAQ</span>
              </nav>
              <h1 class="page-hero__title section__title">Частые <span
                      class="page-hero__title-red">вопросы</span></h1>
          </div>
      </div>
  </section>

  <!-- HELP HUB -->
  <section class="help">
      <div class="container">
          <div class="help__panel">
              <HelpAside active="contact" />

              <div class="help__content">
                  <div class="help__tab help__tab_active">
                      <h2 class="help__tab-title">Оставьте заявку на подключение</h2>
                      <form class="help__form" novalidate>
                          <div class="field">
                              <label class="field__label field__label_dark" for="help-phone">Ваш номер
                                  телефона</label>
                              <input type="tel" class="field__input" id="help-phone"
                                  placeholder="+998(_)___ _" />
                          </div>
                          <div class="field">
                              <label class="field__label field__label_dark" for="help-topic">Тема
                                  обращения</label>
                              <div class="select">
                                  <select class="select__control" id="help-topic">
                                      <option value="" disabled selected>Выберите тему</option>
                                      <option>Подключение</option>
                                      <option>Тарифы и оплата</option>
                                      <option>Техническая поддержка</option>
                                      <option>Другое</option>
                                  </select>
                                  <svg class="select__chevron" viewBox="0 0 12 8" fill="none"
                                      xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                      <path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.6"
                                          stroke-linecap="round" stroke-linejoin="round" />
                                  </svg>
                              </div>
                          </div>
                          <div class="field">
                              <label class="field__label field__label_dark" for="help-message">Чем можем
                                  помочь?</label>
                              <textarea class="field__textarea" id="help-message" placeholder="Введите текст…"
                                  maxlength="500" rows="5"></textarea>
                          </div>
                          <p class="help__counter">Символов осталось: <b data-help-counter>500</b></p>
                          <button type="submit" class="help__submit">Отправить</button>
                          <p class="help__agree">Нажимая «Отправить», вы соглашаетесь с <NuxtLink
                                  :to="localePath('/documents')">политикой обработки персональных данных</NuxtLink>. Мы
                              свяжемся с вами в течение рабочего дня.</p>
                      </form>
                  </div>
              </div>
          </div>
      </div>
  </section>
</template>
