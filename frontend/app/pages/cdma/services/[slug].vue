<script setup lang="ts">
const localePath = useLocalePath()
definePageMeta({ layout: 'cdma' })
useSeo({ title: "Perfectum | Услуга Автоплатёж" })
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
                  <NuxtLink class="cdma-crumbs__link" :to="localePath('/cdma') + '#cdma-services'">Услуги</NuxtLink>
                  <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <span class="cdma-crumbs__current">Автоплатёж</span>
              </nav>
              <h1 class="cdma-hero__title">Автоплатёж</h1>
          </div>
      </section>

      <!-- CDMA SERVICE -->
      <section class="cdma-detail">
          <div class="container">
              <span class="cdma-badge">Дополнительные услуги</span>
              <p class="cdma-detail__lead">Автоматическое пополнение баланса с банковской карты по расписанию.
                  Вы задаёте сумму и периодичность — система списывает и зачисляет без вашего участия.</p>

              <ul class="cdma-facts">
                  <li class="cdma-fact">
                      <span class="cdma-fact__label">Стоимость</span>
                      <span class="cdma-fact__value">Бесплатно</span>
                  </li>
                  <li class="cdma-fact">
                      <span class="cdma-fact__label">Подключение</span>
                      <span class="cdma-fact__value">USSD / ЛК</span>
                  </li>
                  <li class="cdma-fact">
                      <span class="cdma-fact__label">Отключение</span>
                      <span class="cdma-fact__value">В любой момент</span>
                  </li>
              </ul>

              <h2 class="cdma-detail__subhead">Как подключить</h2>
              <ol class="cdma-steps">
                  <li class="cdma-step">
                      <span class="cdma-step__num" aria-hidden="true">1</span>
                      <div class="cdma-step__body">
                          <p class="cdma-step__text">Наберите USSD-команду с телефона</p>
                          <span class="cdma-step__code">*100*5#</span>
                      </div>
                  </li>
                  <li class="cdma-step">
                      <span class="cdma-step__num" aria-hidden="true">2</span>
                      <div class="cdma-step__body">
                          <p class="cdma-step__text">Укажите сумму пополнения и периодичность (раз в неделю, раз в месяц, при балансе ниже порога)</p>
                      </div>
                  </li>
                  <li class="cdma-step">
                      <span class="cdma-step__num" aria-hidden="true">3</span>
                      <div class="cdma-step__body">
                          <p class="cdma-step__text">Привяжите банковскую карту (Uzcard, Humo) — данные защищены</p>
                      </div>
                  </li>
                  <li class="cdma-step">
                      <span class="cdma-step__num" aria-hidden="true">4</span>
                      <div class="cdma-step__body">
                          <p class="cdma-step__text">Готово — пополнение будет происходить автоматически. SMS-уведомление после каждого списания.</p>
                      </div>
                  </li>
              </ol>

              <h2 class="cdma-detail__subhead">Подробное описание</h2>
              <div class="cdma-prose">
                  <p class="cdma-prose__p">Автоплатёж работает с картами Uzcard и Humo. Минимальная сумма
                      пополнения — 5 000 сум, максимальная — 500 000 сум за одну транзакцию.</p>
                  <p class="cdma-prose__p">Вы можете настроить один из трёх режимов: <b>по расписанию</b>
                      (каждую неделю или каждый месяц в выбранный день), <b>по порогу</b> (когда баланс
                      опускается ниже заданной суммы), или <b>комбинированный</b> (по расписанию + по порогу).</p>
                  <p class="cdma-prose__p">Услуга бесплатная, комиссия за транзакции не взимается. Отключить
                      автоплатёж можно в любой момент через USSD-команду <b>*100*5*0#</b> или в личном
                      кабинете.</p>
              </div>

              <NuxtLink class="cdma-back" :to="localePath('/cdma') + '#cdma-services'">
                  <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M19 12H5M11 18l-6-6 6-6" stroke="currentColor" stroke-width="1.8"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  Все услуги
              </NuxtLink>
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
