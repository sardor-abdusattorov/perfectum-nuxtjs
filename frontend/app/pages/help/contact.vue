<script setup lang="ts">
const localePath = useLocalePath()
useSeo({ title: "Perfectum | Помощь — связь с нами" })
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
              <aside class="help__aside" aria-label="Разделы помощи">
                  <button type="button" class="help__aside-tab" aria-label="Показать разделы помощи"
                      aria-expanded="false">
                      <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                          aria-hidden="true">
                          <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                              stroke-linejoin="round" />
                      </svg>
                  </button>
                  <p class="help__aside-title">Помощь</p>
                  <div class="help__nav">
                      <NuxtLink class="help__nav-item" :to="localePath('/help')">
                          <span class="help__nav-icon">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                  viewBox="0 0 24 24" fill="none">
                                  <g clip-path="url(#clip0_1376_2121)">
                                      <path
                                          d="M12 0C9.62663 0 7.30655 0.703788 5.33316 2.02236C3.35977 3.34094 1.8217 5.21509 0.913451 7.4078C0.00519943 9.60051 -0.232441 12.0133 0.230582 14.3411C0.693605 16.6689 1.83649 18.8071 3.51472 20.4853C5.19295 22.1635 7.33115 23.3064 9.65892 23.7694C11.9867 24.2324 14.3995 23.9948 16.5922 23.0866C18.7849 22.1783 20.6591 20.6402 21.9776 18.6668C23.2962 16.6935 24 14.3734 24 12C23.9966 8.81846 22.7312 5.76821 20.4815 3.51852C18.2318 1.26883 15.1815 0.00344108 12 0ZM12 22C10.0222 22 8.08879 21.4135 6.4443 20.3147C4.79981 19.2159 3.51809 17.6541 2.76121 15.8268C2.00433 13.9996 1.8063 11.9889 2.19215 10.0491C2.578 8.10929 3.53041 6.32746 4.92894 4.92893C6.32746 3.53041 8.10929 2.578 10.0491 2.19215C11.9889 1.8063 13.9996 2.00433 15.8268 2.7612C17.6541 3.51808 19.2159 4.79981 20.3147 6.4443C21.4135 8.08879 22 10.0222 22 12C21.9971 14.6513 20.9426 17.1931 19.0679 19.0679C17.1931 20.9426 14.6513 21.9971 12 22Z"
                                          fill="currentColor" />
                                      <path
                                          d="M12.717 5.06283C12.1403 4.95776 11.5476 4.98072 10.9808 5.13007C10.414 5.27943 9.88691 5.55154 9.43689 5.92714C8.98688 6.30274 8.62491 6.77265 8.37661 7.30363C8.12831 7.83461 7.99974 8.41367 8 8.99983C8 9.26505 8.10536 9.5194 8.29289 9.70694C8.48043 9.89448 8.73478 9.99983 9 9.99983C9.26522 9.99983 9.51957 9.89448 9.70711 9.70694C9.89464 9.5194 10 9.26505 10 8.99983C9.99975 8.70561 10.0644 8.41495 10.1894 8.14859C10.3144 7.88223 10.4966 7.64672 10.723 7.45886C10.9495 7.271 11.2146 7.13542 11.4994 7.06178C11.7843 6.98814 12.0819 6.97825 12.371 7.03283C12.766 7.10952 13.1293 7.30222 13.4142 7.58632C13.6992 7.87043 13.8931 8.23304 13.971 8.62783C14.0497 9.04224 13.9954 9.47091 13.8158 9.85257C13.6362 10.2342 13.3405 10.5493 12.971 10.7528C12.3592 11.1073 11.8536 11.6193 11.5069 12.2356C11.1603 12.8519 10.9852 13.5499 11 14.2568V14.9998C11 15.265 11.1054 15.5194 11.2929 15.7069C11.4804 15.8945 11.7348 15.9998 12 15.9998C12.2652 15.9998 12.5196 15.8945 12.7071 15.7069C12.8946 15.5194 13 15.265 13 14.9998V14.2568C12.9875 13.9088 13.0668 13.5637 13.2301 13.2561C13.3933 12.9486 13.6347 12.6894 13.93 12.5048C14.6545 12.1069 15.238 11.4943 15.6001 10.7512C15.9622 10.0082 16.0853 9.17119 15.9523 8.35536C15.8193 7.53952 15.4368 6.78492 14.8575 6.19533C14.2782 5.60573 13.5304 5.2101 12.717 5.06283Z"
                                          fill="currentColor" />
                                      <path
                                          d="M13 18C13 17.4477 12.5523 17 12 17C11.4477 17 11 17.4477 11 18C11 18.5523 11.4477 19 12 19C12.5523 19 13 18.5523 13 18Z"
                                          fill="currentColor" />
                                  </g>
                                  <defs>
                                      <clipPath id="clip0_1376_2121">
                                          <rect width="24" height="24" fill="white" />
                                      </clipPath>
                                  </defs>
                              </svg>
                          </span>
                          Часто задаваемые вопросы
                      </NuxtLink>
                      <NuxtLink class="help__nav-item" :to="localePath('/help/numbers')">
                          <span class="help__nav-icon">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                  viewBox="0 0 24 24" fill="none">
                                  <path
                                      d="M8.9998 6.00041H14.9998M4.7998 4.80039V19.2004C4.7998 20.5259 5.87432 21.6004 7.1998 21.6004H16.7998C18.1253 21.6004 19.1998 20.5259 19.1998 19.2004V4.8004C19.1998 3.47492 18.1253 2.40041 16.7998 2.4004L7.19981 2.40039C5.87432 2.40039 4.7998 3.47491 4.7998 4.80039ZM11.9998 16.8004H12.0848V16.8773H11.9998V16.8004Z"
                                      stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                      stroke-linejoin="round" />
                              </svg>
                          </span>
                          Свободные номера
                      </NuxtLink>
                      <NuxtLink class="help__nav-item help__nav-item_active"
                          aria-current="page" :to="localePath('/help/contact')">
                          <span class="help__nav-icon">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                  viewBox="0 0 24 24" fill="none">
                                  <path
                                      d="M8.39893 8.40039H15.5989M8.39893 13.2004H12.5989M21.5989 12.0004C21.5989 13.3804 21.3077 14.6924 20.7834 15.8783L21.6007 21.5995L16.6978 20.3737C15.3089 21.1549 13.7059 21.6004 11.9989 21.6004C6.69699 21.6004 2.39893 17.3023 2.39893 12.0004C2.39893 6.69846 6.69699 2.40039 11.9989 2.40039C17.3009 2.40039 21.5989 6.69846 21.5989 12.0004Z"
                                      stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                      stroke-linejoin="round" />
                              </svg>
                          </span>
                          Связь с нами
                      </NuxtLink>
                  </div>
              </aside>

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
