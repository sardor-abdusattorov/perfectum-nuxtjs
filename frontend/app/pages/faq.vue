<script setup lang="ts">
const localePath = useLocalePath()
useSeo({ titleKey: 'seo.faq' })
</script>

<template>
  <!-- PAGE HERO -->
  <section class="page-hero page-hero_inner page-hero_faq">
      <div class="container">
          <div class="page-hero__inner">
              <nav class="page-hero__crumbs" aria-label="Хлебные крошки">
                  <NuxtLink class="page-hero__crumb" :to="localePath('/')">Главная</NuxtLink>
                  <svg class="page-hero__crumb-sep" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                      fill="none" aria-hidden="true">
                      <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">Помощь</span>
              </nav>
              <p class="page-hero__eyebrow page-hero__eyebrow_silver">Помощь и поддержка</p>
              <h1 class="page-hero__title section__title">Часто<br>задаваемые <span
                      class="page-hero__title-red">вопросы</span></h1>
              <p class="page-hero__subtitle">Ответы на популярные вопросы о подключении, тарифах, SIM/eSIM и
                  личном кабинете — найдите нужный раздел или воспользуйтесь поиском.</p>
          </div>
      </div>
  </section>

  <!-- FAQ -->
  <section class="faq">
      <div class="container">
          <div class="filter-search">
              <div class="filter-search__field">
                  <input type="search" class="filter-search__input"
                      placeholder="Например: «как подключить eSIM», «не работает интернет»…"
                      aria-label="Поиск по вопросам" />
                  <svg class="filter-search__btn" viewBox="0 0 24 24" fill="none"
                      xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                      <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8" />
                      <path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="1.8"
                          stroke-linecap="round" />
                  </svg>
              </div>
              <div class="filter-search__chips" role="tablist" aria-label="Категории вопросов">
                  <button type="button" class="filter-search__chip filter-search__chip_active" role="tab" aria-selected="true">Все
                      <span class="filter-search__chip-count">22</span></button>
                  <button type="button" class="filter-search__chip" role="tab" aria-selected="false">Подключение <span
                          class="filter-search__chip-count">3</span></button>
                  <button type="button" class="filter-search__chip" role="tab" aria-selected="false">Тарифы и оплата <span
                          class="filter-search__chip-count">3</span></button>
                  <button type="button" class="filter-search__chip" role="tab" aria-selected="false">SIM и eSIM <span
                          class="filter-search__chip-count">3</span></button>
                  <button type="button" class="filter-search__chip" role="tab" aria-selected="false">Домашний интернет
                      <span class="filter-search__chip-count">3</span></button>
                  <button type="button" class="filter-search__chip" role="tab" aria-selected="false">Личный кабинет <span
                          class="filter-search__chip-count">3</span></button>
                  <button type="button" class="filter-search__chip" role="tab" aria-selected="false">Техническая
                      поддержка <span class="filter-search__chip-count">3</span></button>
              </div>
          </div>

          <div class="section-head">
              <h2 class="section-head__title">Все вопросы</h2>
              <span class="section-head__count">15 вопросов</span>
          </div>

          <div class="faq-accordion">
              <details class="faq-item">
                  <summary class="faq-item__summary">
                      <span class="faq-item__question">Как подключиться к Perfectum?</span>
                      <span class="faq-item__toggle" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" />
                          </svg>
                      </span>
                  </summary>
                  <div class="faq-item__answer">
                      <p>Оставьте заявку на сайте или в приложении, выберите тариф — специалист свяжется с вами
                          и поможет с активацией.</p>
                  </div>
              </details>
              <details class="faq-item" open>
                  <summary class="faq-item__summary">
                      <span class="faq-item__question">Сколько времени занимает подключение домашнего
                          интернета?</span>
                      <span class="faq-item__toggle" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" />
                          </svg>
                      </span>
                  </summary>
                  <div class="faq-item__answer">
                      <p>В зоне покрытия 5G SA подключение обычно занимает 1–2 дня после оформления заявки и
                          установки оборудования.</p>
                  </div>
              </details>
              <details class="faq-item">
                  <summary class="faq-item__summary">
                      <span class="faq-item__question">Нужен ли отдельный роутер для 5G SA интернета?</span>
                      <span class="faq-item__toggle" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" />
                          </svg>
                      </span>
                  </summary>
                  <div class="faq-item__answer">
                      <p>Да, для домашнего интернета используется 5G-роутер — его можно приобрести или взять в
                          аренду при подключении.</p>
                  </div>
              </details>
              <details class="faq-item">
                  <summary class="faq-item__summary">
                      <span class="faq-item__question">Как сменить тарифный план?</span>
                      <span class="faq-item__toggle" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" />
                          </svg>
                      </span>
                  </summary>
                  <div class="faq-item__answer">
                      <p>Сменить тариф можно в личном кабинете или приложении в разделе «Тариф» — изменения
                          вступают в силу со следующего расчётного периода.</p>
                  </div>
              </details>
              <details class="faq-item">
                  <summary class="faq-item__summary">
                      <span class="faq-item__question">Можно ли заморозить тариф на время отъезда?</span>
                      <span class="faq-item__toggle" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" />
                          </svg>
                      </span>
                  </summary>
                  <div class="faq-item__answer">
                      <p>Да, доступна услуга «Добровольная блокировка» — номер сохраняется, а абонентская плата
                          не списывается.</p>
                  </div>
              </details>
              <details class="faq-item">
                  <summary class="faq-item__summary">
                      <span class="faq-item__question">Какие способы оплаты доступны?</span>
                      <span class="faq-item__toggle" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" />
                          </svg>
                      </span>
                  </summary>
                  <div class="faq-item__answer">
                      <p>Банковские карты, платёжные системы (Payme, Click, Uzum), терминалы и оплата через
                          личный кабинет.</p>
                  </div>
              </details>
              <details class="faq-item">
                  <summary class="faq-item__summary">
                      <span class="faq-item__question">Как оформить eSIM?</span>
                      <span class="faq-item__toggle" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" />
                          </svg>
                      </span>
                  </summary>
                  <div class="faq-item__answer">
                      <p>Выберите eSIM при оформлении, оплатите и отсканируйте QR-код в настройках телефона —
                          номер активируется сразу.</p>
                  </div>
              </details>
              <details class="faq-item">
                  <summary class="faq-item__summary">
                      <span class="faq-item__question">Что делать при утере SIM-карты?</span>
                      <span class="faq-item__toggle" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" />
                          </svg>
                      </span>
                  </summary>
                  <div class="faq-item__answer">
                      <p>Заблокируйте номер в приложении или по звонку в поддержку и восстановите SIM в любом
                          офисе продаж с документом.</p>
                  </div>
              </details>
              <details class="faq-item">
                  <summary class="faq-item__summary">
                      <span class="faq-item__question">Поддерживает ли мой телефон eSIM?</span>
                      <span class="faq-item__toggle" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" />
                          </svg>
                      </span>
                  </summary>
                  <div class="faq-item__answer">
                      <p>Большинство современных смартфонов поддерживают eSIM. Проверить можно в настройках
                          устройства или в списке совместимых моделей.</p>
                  </div>
              </details>
              <details class="faq-item">
                  <summary class="faq-item__summary">
                      <span class="faq-item__question">В каких районах доступен домашний интернет?</span>
                      <span class="faq-item__toggle" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" />
                          </svg>
                      </span>
                  </summary>
                  <div class="faq-item__answer">
                      <p>Проверьте доступность на странице «Карта покрытия» — там показаны все зоны, где работает
                          5G SA интернет.</p>
                  </div>
              </details>
              <details class="faq-item">
                  <summary class="faq-item__summary">
                      <span class="faq-item__question">Что делать, если интернет пропал?</span>
                      <span class="faq-item__toggle" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" />
                          </svg>
                      </span>
                  </summary>
                  <div class="faq-item__answer">
                      <p>Перезагрузите роутер, проверьте баланс и статус услуги в кабинете. Если не помогло —
                          напишите в поддержку.</p>
                  </div>
              </details>
              <details class="faq-item">
                  <summary class="faq-item__summary">
                      <span class="faq-item__question">Как восстановить доступ к личному кабинету?</span>
                      <span class="faq-item__toggle" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" />
                          </svg>
                      </span>
                  </summary>
                  <div class="faq-item__answer">
                      <p>Нажмите «Забыли пароль» на странице входа и восстановите доступ по SMS-коду на ваш
                          номер.</p>
                  </div>
              </details>
              <details class="faq-item">
                  <summary class="faq-item__summary">
                      <span class="faq-item__question">Можно ли управлять несколькими номерами из одного
                          аккаунта?</span>
                      <span class="faq-item__toggle" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" />
                          </svg>
                      </span>
                  </summary>
                  <div class="faq-item__answer">
                      <p>Да, к одному аккаунту можно привязать несколько номеров и переключаться между ними в
                          приложении.</p>
                  </div>
              </details>
              <details class="faq-item">
                  <summary class="faq-item__summary">
                      <span class="faq-item__question">Как связаться с поддержкой?</span>
                      <span class="faq-item__toggle" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" />
                          </svg>
                      </span>
                  </summary>
                  <div class="faq-item__answer">
                      <p>Позвоните на 077, напишите в Telegram @Perfectum_Support или на hotline@perfectum.uz —
                          поможем в любое время.</p>
                  </div>
              </details>
              <details class="faq-item">
                  <summary class="faq-item__summary">
                      <span class="faq-item__question">Где посмотреть детализацию звонков и трафика?</span>
                      <span class="faq-item__toggle" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" />
                          </svg>
                      </span>
                  </summary>
                  <div class="faq-item__answer">
                      <p>Детализация доступна в личном кабинете и приложении в разделе «История» — можно выгрузить
                          за нужный период.</p>
                  </div>
              </details>
          </div>

          <!-- SUPPORT CTA -->
          <div class="support-cta">
              <h2 class="support-cta__title">Не нашли ответ? Напишите в поддержку — ответим в течение дня.</h2>
              <a href="#" class="support-cta__btn">Связаться с поддержкой</a>
          </div>
      </div>
  </section>
</template>
