<script setup lang="ts">
const localePath = useLocalePath()
const t = useT()

definePageMeta({ layout: 'cdma' })
useSeo({ page: 'cdma', titleKey: 'seo.cdma' })

const { data: faqData } = await useFaqs({ page: 'cdma' })
const { data: newsData } = await useNewsList({ network: 'cdma', perPage: 24 })
const { data: actionsData } = await useActionsList({ network: 'cdma', perPage: 12 })

const faqs = computed(() => faqData.value?.faqs ?? [])
const { locale } = useI18n()

const newsYear = ref('')
const newsMonth = ref('')

const allNews = computed(() => newsData.value?.items ?? [])

const newsYears = computed(() => [...new Set(allNews.value.map(item => item.published_at?.slice(0, 4)).filter(Boolean))] as string[])

const newsMonths = computed(() => [...new Set(
  allNews.value
    .filter(item => !newsYear.value || item.published_at?.startsWith(newsYear.value))
    .map(item => item.published_at?.slice(5, 7))
    .filter(Boolean),
)] as string[])

const news = computed(() => allNews.value.filter(item => (
  (!newsYear.value || item.published_at?.startsWith(newsYear.value))
  && (!newsMonth.value || item.published_at?.slice(5, 7) === newsMonth.value)
)))

watch(newsYear, () => {
  newsMonth.value = ''
})

function monthName(month: string): string {
  return new Intl.DateTimeFormat(locale.value === 'uz' ? 'uz-UZ' : 'ru-RU', { month: 'long' })
    .format(new Date(2026, Number(month) - 1, 1))
}

const promos = computed(() => actionsData.value?.items ?? [])
const PROMO_COVERS = ['cdma-promo-card__cover_orange', 'cdma-promo-card__cover_red', 'cdma-promo-card__cover_sale']
const RAIL_OPTIONS = {
  spaceBetween: 16,
  watchOverflow: true,
  freeMode: true,
  breakpoints: {
    0: { slidesPerView: 1.06, spaceBetween: 16 },
    440: { slidesPerView: 1.2, spaceBetween: 16 },
    520: { slidesPerView: 1.42, spaceBetween: 16 },
    600: { slidesPerView: 1.6, spaceBetween: 20 },
    769: { slidesPerView: 2.05, spaceBetween: 20 },
    900: { slidesPerView: 2.25, spaceBetween: 20 },
    993: { slidesPerView: 2.7, spaceBetween: 24 },
    1201: { slidesPerView: 3.2, spaceBetween: 24 },
    1401: { slidesPerView: 3.75, spaceBetween: 24 },
    1700: { slidesPerView: 4.5, spaceBetween: 24 },
  },
}

const tariffRail = useTemplateRef('tariffRail')
const serviceRail = useTemplateRef('serviceRail')

useSlider(tariffRail, { ...RAIL_OPTIONS, scrollbar: { el: '#cdma-tariffs .cdma-rail__bar', draggable: true } }, () => locale.value)
useSlider(serviceRail, { ...RAIL_OPTIONS, scrollbar: { el: '#cdma-services .cdma-rail__bar', draggable: true } }, () => locale.value)
</script>


<template>
  <!-- CDMA HERO -->
  <section class="cdma-hero">
      <div class="container">
          <h1 class="cdma-hero__title">CDMA</h1>
          <p class="cdma-hero__subtitle">Всё для действующих и новых абонентов - в одном месте.</p>
      </div>
  </section>

  <!-- CDMA TABS -->
  <nav class="cdma-tabs" aria-label="Разделы CDMA">
      <div class="container">
          <ul class="cdma-tabs__list">
              <li class="cdma-tabs__item">
                  <a class="cdma-tabs__link cdma-tabs__link_active" href="#cdma-tariffs">Тарифы</a>
              </li>
              <li class="cdma-tabs__item">
                  <a class="cdma-tabs__link" href="#cdma-services">Услуги</a>
              </li>
              <li class="cdma-tabs__item">
                  <a class="cdma-tabs__link" href="#cdma-numbers">Номера</a>
              </li>
              <li class="cdma-tabs__item">
                  <a class="cdma-tabs__link" href="#cdma-faq">FAQ</a>
              </li>
              <li class="cdma-tabs__item">
                  <a class="cdma-tabs__link" href="#cdma-support">Поддержка</a>
              </li>
              <li class="cdma-tabs__item">
                  <a class="cdma-tabs__link" href="#cdma-news">Новости</a>
              </li>
              <li class="cdma-tabs__item">
                  <a class="cdma-tabs__link" href="#cdma-promo">Акции</a>
              </li>
              <li class="cdma-tabs__item">
                  <NuxtLink class="cdma-tabs__link" :to="localePath('/cdma/dealers')">Дилеры</NuxtLink>
              </li>
          </ul>
          <NuxtLink class="cdma-tabs__connect" :to="localePath('/cdma/connect')">Подключиться</NuxtLink>
      </div>
  </nav>

  <!-- CDMA TARIFFS -->
  <section class="cdma-section" id="cdma-tariffs">
      <div class="container">
          <h2 class="cdma-section__title">Тарифы</h2>
          <ul class="cdma-chips">
              <li class="cdma-chips__item">
                  <button type="button" class="cdma-chips__btn cdma-chips__btn_active">Все</button>
              </li>
              <li class="cdma-chips__item">
                  <button type="button" class="cdma-chips__btn">“Qulay” ежемесячные</button>
              </li>
              <li class="cdma-chips__item">
                  <button type="button" class="cdma-chips__btn">“Qulay” полугодовые</button>
              </li>
              <li class="cdma-chips__item">
                  <button type="button" class="cdma-chips__btn">Специальные тарифы</button>
              </li>
              <li class="cdma-chips__item">
                  <button type="button" class="cdma-chips__btn">Полугодовые (6+K)</button>
              </li>
          </ul>

          <div ref="tariffRail" class="cdma-rail swiper">
              <ul class="cdma-rail__track swiper-wrapper">
                  <li class="cdma-tariff-card swiper-slide">
                      <span class="cdma-tariff-card__badge">Qulay ежемесячные</span>
                      <h3 class="cdma-tariff-card__name">Qulay 30</h3>
                      <p class="cdma-tariff-card__price">30 000<span class="cdma-tariff-card__period">сум /
                              месяц</span></p>
                      <ul class="cdma-tariff-card__list">
                          <li class="cdma-tariff-card__feature">300 минут по Узбекистану</li>
                          <li class="cdma-tariff-card__feature">300 SMS</li>
                          <li class="cdma-tariff-card__feature">5 ГБ интернета</li>
                      </ul>
                      <NuxtLink class="cdma-tariff-card__more cdma-tariff-card__more_active"
                          :to="localePath('/cdma/tariffs/example')">Подробнее</NuxtLink>
                  </li>
                  <li class="cdma-tariff-card swiper-slide">
                      <span class="cdma-tariff-card__badge">Qulay ежемесячные</span>
                      <h3 class="cdma-tariff-card__name">Qulay 50</h3>
                      <p class="cdma-tariff-card__price">50 000<span class="cdma-tariff-card__period">сум /
                              месяц</span></p>
                      <ul class="cdma-tariff-card__list">
                          <li class="cdma-tariff-card__feature">500 минут по Узбекистану</li>
                          <li class="cdma-tariff-card__feature">500 SMS</li>
                          <li class="cdma-tariff-card__feature">10 ГБ интернета</li>
                      </ul>
                      <NuxtLink class="cdma-tariff-card__more" :to="localePath('/cdma/tariffs/example')">Подробнее</NuxtLink>
                  </li>
                  <li class="cdma-tariff-card swiper-slide">
                      <span class="cdma-tariff-card__badge">Qulay ежемесячные</span>
                      <h3 class="cdma-tariff-card__name">Qulay 80</h3>
                      <p class="cdma-tariff-card__price">80 000<span class="cdma-tariff-card__period">сум /
                              месяц</span></p>
                      <ul class="cdma-tariff-card__list">
                          <li class="cdma-tariff-card__feature">1000 минут по Узбекистану</li>
                          <li class="cdma-tariff-card__feature">1000 SMS</li>
                          <li class="cdma-tariff-card__feature">20 ГБ интернета</li>
                      </ul>
                      <NuxtLink class="cdma-tariff-card__more" :to="localePath('/cdma/tariffs/example')">Подробнее</NuxtLink>
                  </li>
                  <li class="cdma-tariff-card swiper-slide">
                      <span class="cdma-tariff-card__badge">Qulay полугодовые</span>
                      <h3 class="cdma-tariff-card__name">Qulay 6M Старт</h3>
                      <p class="cdma-tariff-card__price">150 000<span class="cdma-tariff-card__period">сум / 6
                              месяц</span></p>
                      <ul class="cdma-tariff-card__list">
                          <li class="cdma-tariff-card__feature">200 минут по Узбекистану</li>
                          <li class="cdma-tariff-card__feature">200 SMS / мес</li>
                          <li class="cdma-tariff-card__feature">3 ГБ мес интернета</li>
                      </ul>
                      <NuxtLink class="cdma-tariff-card__more" :to="localePath('/cdma/tariffs/example')">Подробнее</NuxtLink>
                  </li>
                  <li class="cdma-tariff-card swiper-slide">
                      <span class="cdma-tariff-card__badge">Qulay полугодовые</span>
                      <h3 class="cdma-tariff-card__name">Qulay 6M Плюс</h3>
                      <p class="cdma-tariff-card__price">150 000<span class="cdma-tariff-card__period">сум / 6
                              месяц</span></p>
                      <ul class="cdma-tariff-card__list">
                          <li class="cdma-tariff-card__feature">200 минут по Узбекистану</li>
                          <li class="cdma-tariff-card__feature">200 SMS / мес</li>
                          <li class="cdma-tariff-card__feature">3 ГБ мес интернета</li>
                      </ul>
                      <NuxtLink class="cdma-tariff-card__more" :to="localePath('/cdma/tariffs/example')">Подробнее</NuxtLink>
                  </li>
              </ul>
              <div class="cdma-rail__bar swiper-scrollbar"></div>
          </div>
      </div>
  </section>

  <!-- CDMA SERVICES -->
  <section class="cdma-section" id="cdma-services">
      <div class="container">
          <h2 class="cdma-section__title">Услуги</h2>
          <ul class="cdma-chips">
              <li class="cdma-chips__item">
                  <button type="button" class="cdma-chips__btn cdma-chips__btn_active">Все</button>
              </li>
              <li class="cdma-chips__item">
                  <button type="button" class="cdma-chips__btn">Сетевые услуги</button>
              </li>
              <li class="cdma-chips__item">
                  <button type="button" class="cdma-chips__btn">Дополнительные услуги</button>
              </li>
              <li class="cdma-chips__item">
                  <button type="button" class="cdma-chips__btn">Мобильный кабинет</button>
              </li>
              <li class="cdma-chips__item">
                  <button type="button" class="cdma-chips__btn">Программа лояльности</button>
              </li>
              <li class="cdma-chips__item">
                  <button type="button" class="cdma-chips__btn">Узнать баланс</button>
              </li>
              <li class="cdma-chips__item">
                  <button type="button" class="cdma-chips__btn">Абонентские услуги</button>
              </li>
              <li class="cdma-chips__item">
                  <button type="button" class="cdma-chips__btn">Международная связь</button>
              </li>
              <li class="cdma-chips__item">
                  <button type="button" class="cdma-chips__btn">Услуги от Контент-провайдеров</button>
              </li>
          </ul>

          <div ref="serviceRail" class="cdma-rail swiper">
              <ul class="cdma-rail__track swiper-wrapper">
                  <li class="cdma-service-card swiper-slide"><NuxtLink class="cdma-service-card__link"
                          :to="localePath('/cdma/services/example')">
                          <div class="cdma-service-card__head">
                              <span class="cdma-service-card__icon" aria-hidden="true">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="24"
                                      viewBox="0 0 20 24" fill="none">
                                      <path
                                          d="M15 14C15 14.2652 14.8946 14.5196 14.7071 14.7071C14.5196 14.8947 14.2652 15 14 15H6C5.73478 15 5.48043 14.8947 5.29289 14.7071C5.10536 14.5196 5 14.2652 5 14C5 13.7348 5.10536 13.4805 5.29289 13.2929C5.48043 13.1054 5.73478 13 6 13H14C14.2652 13 14.5196 13.1054 14.7071 13.2929C14.8946 13.4805 15 13.7348 15 14ZM11 17H6C5.73478 17 5.48043 17.1054 5.29289 17.2929C5.10536 17.4805 5 17.7348 5 18C5 18.2652 5.10536 18.5196 5.29289 18.7071C5.48043 18.8947 5.73478 19 6 19H11C11.2652 19 11.5196 18.8947 11.7071 18.7071C11.8946 18.5196 12 18.2652 12 18C12 17.7348 11.8946 17.4805 11.7071 17.2929C11.5196 17.1054 11.2652 17 11 17ZM20 10.485V19C19.9984 20.3256 19.4711 21.5965 18.5338 22.5338C17.5964 23.4711 16.3256 23.9984 15 24H5C3.67441 23.9984 2.40356 23.4711 1.46622 22.5338C0.528882 21.5965 0.00158786 20.3256 0 19V5.00002C0.00158786 3.67443 0.528882 2.40358 1.46622 1.46624C2.40356 0.528905 3.67441 0.00161091 5 2.30487e-05H9.515C10.4346 -0.00234388 11.3456 0.177611 12.1952 0.529482C13.0449 0.881354 13.8163 1.39816 14.465 2.05002L17.949 5.53602C18.6012 6.18426 19.1184 6.95548 19.4704 7.805C19.8225 8.65451 20.0025 9.56545 20 10.485ZM13.051 3.46402C12.7363 3.15918 12.3829 2.89695 12 2.68402V7.00002C12 7.26524 12.1054 7.51959 12.2929 7.70713C12.4804 7.89467 12.7348 8.00002 13 8.00002H17.316C17.103 7.61721 16.8404 7.26417 16.535 6.95002L13.051 3.46402ZM18 10.485C18 10.32 17.968 10.162 17.953 10H13C12.2044 10 11.4413 9.68395 10.8787 9.12134C10.3161 8.55873 10 7.79567 10 7.00002V2.04702C9.838 2.03202 9.679 2.00002 9.515 2.00002H5C4.20435 2.00002 3.44129 2.31609 2.87868 2.8787C2.31607 3.44131 2 4.20437 2 5.00002V19C2 19.7957 2.31607 20.5587 2.87868 21.1213C3.44129 21.684 4.20435 22 5 22H15C15.7956 22 16.5587 21.684 17.1213 21.1213C17.6839 20.5587 18 19.7957 18 19V10.485Z"
                                          fill="currentColor" />
                                  </svg>
                              </span>
                              <h3 class="cdma-service-card__name">Детализация звонков</h3>
                          </div>
                          <p class="cdma-service-card__text">Отчёт по звонкам, SMS и интернет-трафику за
                              выбранный период.</p>
                          <span class="cdma-service-card__code">*100*3#</span>
                      </NuxtLink>
                  </li>
                  <li class="cdma-service-card swiper-slide"><NuxtLink class="cdma-service-card__link"
                          :to="localePath('/cdma/services/example')">
                          <div class="cdma-service-card__head">
                              <span class="cdma-service-card__icon" aria-hidden="true">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                      viewBox="0 0 24 24" fill="none">
                                      <g clip-path="url(#clip0_1523_1177)">
                                          <path
                                              d="M12 0C9.62663 0 7.30655 0.703788 5.33316 2.02236C3.35977 3.34094 1.8217 5.21509 0.913451 7.4078C0.00519943 9.60051 -0.232441 12.0133 0.230582 14.3411C0.693605 16.6689 1.83649 18.8071 3.51472 20.4853C5.19295 22.1635 7.33115 23.3064 9.65892 23.7694C11.9867 24.2324 14.3995 23.9948 16.5922 23.0866C18.7849 22.1783 20.6591 20.6402 21.9776 18.6668C23.2962 16.6935 24 14.3734 24 12C23.9966 8.81846 22.7312 5.76821 20.4815 3.51852C18.2318 1.26883 15.1815 0.00344108 12 0ZM20.647 7H17.426C16.705 5.32899 15.7556 3.76609 14.605 2.356C17.1515 3.04893 19.3223 4.71747 20.647 7ZM16.5 12C16.4918 13.0181 16.3314 14.0293 16.024 15H7.97601C7.66866 14.0293 7.50821 13.0181 7.50001 12C7.50821 10.9819 7.66866 9.97068 7.97601 9H16.024C16.3314 9.97068 16.4918 10.9819 16.5 12ZM8.77801 17H15.222C14.3732 18.6757 13.2882 20.2208 12 21.588C10.7114 20.2212 9.62625 18.676 8.77801 17ZM8.77801 7C9.62677 5.32427 10.7119 3.77916 12 2.412C13.2886 3.77877 14.3738 5.32396 15.222 7H8.77801ZM9.40001 2.356C8.24767 3.76578 7.29659 5.3287 6.57401 7H3.35301C4.67886 4.71643 6.85166 3.04775 9.40001 2.356ZM2.46101 9H5.90001C5.64076 9.97915 5.50636 10.9871 5.50001 12C5.50636 13.0129 5.64076 14.0209 5.90001 15H2.46101C1.84635 13.0472 1.84635 10.9528 2.46101 9ZM3.35301 17H6.57401C7.29659 18.6713 8.24767 20.2342 9.40001 21.644C6.85166 20.9522 4.67886 19.2836 3.35301 17ZM14.605 21.644C15.7556 20.2339 16.705 18.671 17.426 17H20.647C19.3223 19.2825 17.1515 20.9511 14.605 21.644ZM21.539 15H18.1C18.3592 14.0209 18.4936 13.0129 18.5 12C18.4936 10.9871 18.3592 9.97915 18.1 9H21.537C22.1517 10.9528 22.1517 13.0472 21.537 15H21.539Z"
                                              fill="currentColor" />
                                      </g>
                                      <defs>
                                          <clipPath id="clip0_1523_1177">
                                              <rect width="24" height="24" fill="white" />
                                          </clipPath>
                                      </defs>
                                  </svg>
                              </span>
                              <h3 class="cdma-service-card__name">Международная связь</h3>
                          </div>
                          <p class="cdma-service-card__text">Звонки в 190+ стран по тарифам оператора без
                              подключения роуминга.</p>
                          <span class="cdma-service-card__code">*100*7#</span>
                      </NuxtLink>
                  </li>
                  <li class="cdma-service-card swiper-slide"><NuxtLink class="cdma-service-card__link"
                          :to="localePath('/cdma/services/example')">
                          <div class="cdma-service-card__head">
                              <span class="cdma-service-card__icon" aria-hidden="true">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                      viewBox="0 0 24 24" fill="none">
                                      <path
                                          d="M12.022 22.9708C5.944 22.9708 1 18.0388 1 11.9778C1 6.27683 5.285 1.57284 10.968 1.03384C11.411 0.982835 11.813 1.23684 11.983 1.63784C12.154 2.03784 12.045 2.50384 11.714 2.78684C10.62 3.72484 10.088 5.34684 10.088 7.74383C10.088 12.8718 12.452 13.9018 16.267 13.9018C18.729 13.9018 20.307 13.3868 21.235 12.2818C21.515 11.9458 21.984 11.8348 22.386 12.0018C22.79 12.1708 23.037 12.5828 22.995 13.0188C22.46 18.6918 17.742 22.9698 12.022 22.9698V22.9708ZM8.78 3.57583C5.362 4.87183 3 8.14984 3 11.9768C3 16.9358 7.048 20.9698 12.022 20.9698C15.854 20.9698 19.137 18.6248 20.443 15.2298C19.027 15.8088 17.482 15.9028 16.266 15.9028C12.755 15.9028 8.087 15.0568 8.087 7.74483C8.087 6.09184 8.317 4.70884 8.78 3.57583Z"
                                          fill="currentColor" />
                                  </svg>
                              </span>
                              <h3 class="cdma-service-card__name">Автоплатёж</h3>
                          </div>
                          <p class="cdma-service-card__text">Автоматическое пополнение баланса с банковской
                              карты по расписанию.</p>
                          <span class="cdma-service-card__code">*100*5#</span>
                      </NuxtLink>
                  </li>
                  <li class="cdma-service-card swiper-slide"><NuxtLink class="cdma-service-card__link"
                          :to="localePath('/cdma/services/example')">
                          <div class="cdma-service-card__head">
                              <span class="cdma-service-card__icon" aria-hidden="true">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                      viewBox="0 0 24 24" fill="none">
                                      <path
                                          d="M19 1H5C3.67441 1.00159 2.40356 1.52888 1.46622 2.46622C0.528882 3.40356 0.00158786 4.67441 0 6L0 18C0.00158786 19.3256 0.528882 20.5964 1.46622 21.5338C2.40356 22.4711 3.67441 22.9984 5 23H19C20.3256 22.9984 21.5964 22.4711 22.5338 21.5338C23.4711 20.5964 23.9984 19.3256 24 18V6C23.9984 4.67441 23.4711 3.40356 22.5338 2.46622C21.5964 1.52888 20.3256 1.00159 19 1ZM5 3H19C19.5988 3.00118 20.1835 3.18151 20.679 3.5178C21.1744 3.85409 21.5579 4.33095 21.78 4.887L14.122 12.546C13.5584 13.1073 12.7954 13.4225 12 13.4225C11.2046 13.4225 10.4416 13.1073 9.878 12.546L2.22 4.887C2.44215 4.33095 2.82561 3.85409 3.32105 3.5178C3.81648 3.18151 4.40121 3.00118 5 3ZM19 21H5C4.20435 21 3.44129 20.6839 2.87868 20.1213C2.31607 19.5587 2 18.7956 2 18V7.5L8.464 13.96C9.40263 14.8963 10.6743 15.422 12 15.422C13.3257 15.422 14.5974 14.8963 15.536 13.96L22 7.5V18C22 18.7956 21.6839 19.5587 21.1213 20.1213C20.5587 20.6839 19.7956 21 19 21Z"
                                          fill="currentColor" />
                                  </svg>
                              </span>
                              <h3 class="cdma-service-card__name">SMS-уведомления</h3>
                          </div>
                          <p class="cdma-service-card__text">Оповещения о списаниях, пополнениях и окончании
                              пакетов услуг.</p>
                          <span class="cdma-service-card__code">*100*4#</span>
                      </NuxtLink>
                  </li>
                  <li class="cdma-service-card swiper-slide"><NuxtLink class="cdma-service-card__link"
                          :to="localePath('/cdma/services/example')">
                          <div class="cdma-service-card__head">
                              <span class="cdma-service-card__icon" aria-hidden="true">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                      viewBox="0 0 24 24" fill="none">
                                      <g clip-path="url(#clip0_1524_1211)">
                                          <path
                                              d="M19 2H18V1C18 0.734784 17.8946 0.48043 17.7071 0.292893C17.5196 0.105357 17.2652 0 17 0C16.7348 0 16.4804 0.105357 16.2929 0.292893C16.1054 0.48043 16 0.734784 16 1V2H8V1C8 0.734784 7.89464 0.48043 7.70711 0.292893C7.51957 0.105357 7.26522 0 7 0C6.73478 0 6.48043 0.105357 6.29289 0.292893C6.10536 0.48043 6 0.734784 6 1V2H5C3.67441 2.00159 2.40356 2.52888 1.46622 3.46622C0.528882 4.40356 0.00158786 5.67441 0 7L0 19C0.00158786 20.3256 0.528882 21.5964 1.46622 22.5338C2.40356 23.4711 3.67441 23.9984 5 24H19C20.3256 23.9984 21.5964 23.4711 22.5338 22.5338C23.4711 21.5964 23.9984 20.3256 24 19V7C23.9984 5.67441 23.4711 4.40356 22.5338 3.46622C21.5964 2.52888 20.3256 2.00159 19 2ZM2 7C2 6.20435 2.31607 5.44129 2.87868 4.87868C3.44129 4.31607 4.20435 4 5 4H19C19.7956 4 20.5587 4.31607 21.1213 4.87868C21.6839 5.44129 22 6.20435 22 7V8H2V7ZM19 22H5C4.20435 22 3.44129 21.6839 2.87868 21.1213C2.31607 20.5587 2 19.7956 2 19V10H22V19C22 19.7956 21.6839 20.5587 21.1213 21.1213C20.5587 21.6839 19.7956 22 19 22Z"
                                              fill="currentColor" />
                                          <path
                                              d="M12 16.5C12.8284 16.5 13.5 15.8284 13.5 15C13.5 14.1716 12.8284 13.5 12 13.5C11.1716 13.5 10.5 14.1716 10.5 15C10.5 15.8284 11.1716 16.5 12 16.5Z"
                                              fill="currentColor" />
                                          <path
                                              d="M7 16.5C7.82843 16.5 8.5 15.8284 8.5 15C8.5 14.1716 7.82843 13.5 7 13.5C6.17157 13.5 5.5 14.1716 5.5 15C5.5 15.8284 6.17157 16.5 7 16.5Z"
                                              fill="currentColor" />
                                          <path
                                              d="M17 16.5C17.8284 16.5 18.5 15.8284 18.5 15C18.5 14.1716 17.8284 13.5 17 13.5C16.1716 13.5 15.5 14.1716 15.5 15C15.5 15.8284 16.1716 16.5 17 16.5Z"
                                              fill="currentColor" />
                                      </g>
                                      <defs>
                                          <clipPath id="clip0_1524_1211">
                                              <rect width="24" height="24" fill="white" />
                                          </clipPath>
                                      </defs>
                                  </svg>
                              </span>
                              <h3 class="cdma-service-card__name">Обещанный платёж</h3>
                          </div>
                          <p class="cdma-service-card__text">Плюс к балансу, когда нужно оставаться на связи.
                          </p>
                          <span class="cdma-service-card__code">*106#</span>
                      </NuxtLink>
                  </li>
              </ul>
              <div class="cdma-rail__bar swiper-scrollbar"></div>
          </div>
      </div>
  </section>

  <!-- CDMA NUMBERS -->
  <section class="cdma-section" id="cdma-numbers">
      <div class="container">
          <h2 class="cdma-section__title">Свободные номера</h2>
          <CdmaFreeNumbers />
      </div>
  </section>

  <!-- CDMA NEWS -->
  <section class="cdma-section" id="cdma-news">
      <div class="container">
          <div class="cdma-section__head">
              <h2 class="cdma-section__title">{{ t('cdma.news_title') }}</h2>
              <div class="cdma-section__filters">
                  <div class="select select_compact">
                      <select v-model="newsYear" class="select__control" :aria-label="t('cdma.year')">
                          <option value="">{{ t('cdma.all_years') }}</option>
                          <option v-for="year in newsYears" :key="year" :value="year">{{ year }}</option>
                      </select>
                      <svg class="select__chevron" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                          <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="1.5"
                              stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                  </div>
                  <div class="select select_compact">
                      <select v-model="newsMonth" class="select__control" :aria-label="t('cdma.month')">
                          <option value="">{{ t('cdma.all_months') }}</option>
                          <option v-for="month in newsMonths" :key="month" :value="month">{{ monthName(month) }}</option>
                      </select>
                      <svg class="select__chevron" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                          <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="1.5"
                              stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                  </div>
              </div>
          </div>
          <ul class="cdma-news">
              <li v-for="item in news" :key="item.slug" class="cdma-news-card">
                  <span class="cdma-news-card__date">{{ dateLong(item.published_at, locale) }}</span>
                  <h3 class="cdma-news-card__title"><NuxtLink class="cdma-news-card__link"
                          :to="localePath(`/cdma/news/${item.slug}`)">{{ item.title }}</NuxtLink></h3>
                  <span v-if="item.category" class="cdma-news-card__cat">{{ item.category.name }}</span>
              </li>
          </ul>
      </div>
  </section>

  <!-- CDMA PROMO -->
  <section class="cdma-section" id="cdma-promo">
      <div class="container">
          <h2 class="cdma-section__title">{{ t('cdma.promo_title') }}</h2>
          <ul class="cdma-promo">
              <li v-for="(item, index) in promos" :key="item.slug" class="cdma-promo-card">
                  <NuxtLink class="cdma-promo-card__cover" :class="PROMO_COVERS[index % PROMO_COVERS.length]"
                      :to="localePath(`/cdma/actions/${item.slug}`)">{{ item.badge ?? item.title }}</NuxtLink>
                  <div class="cdma-promo-card__body">
                      <span class="cdma-promo-card__date">{{ dateLong(item.starts_at, locale) }}</span>
                      <h3 class="cdma-promo-card__title">{{ item.title }}</h3>
                      <span class="cdma-promo-card__cat">{{ item.excerpt }}</span>
                  </div>
              </li>
          </ul>
      </div>
  </section>

  <!-- CDMA FAQ -->
  <section class="cdma-section" id="cdma-faq">
      <div class="container">
          <h2 class="cdma-section__title">{{ t('cdma.faq_title') }}</h2>
          <FaqAccordion v-if="faqs.length" class="faq-accordion_cdma" :items="faqs" />
      </div>
  </section>

  <!-- CDMA SUPPORT -->
  <section class="cdma-section" id="cdma-support">
      <div class="container">
          <h2 class="cdma-section__title">Поддержка</h2>
          <ul class="cdma-support">
              <li class="cdma-support-card">
                  <div class="cdma-support-card__head">
                      <span class="cdma-support-card__icon" aria-hidden="true">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                              fill="none">
                              <path
                                  d="M17.7071 13.7071L20.3552 16.3552C20.7113 16.7113 20.7113 17.2887 20.3552 17.6448C18.43 19.57 15.3821 19.7866 13.204 18.153L11.6286 16.9714C9.88504 15.6638 8.33622 14.115 7.02857 12.3714L5.84701 10.796C4.21341 8.61788 4.43001 5.56999 6.35523 3.64477C6.71133 3.28867 7.28867 3.28867 7.64477 3.64477L10.2929 6.29289C10.6834 6.68342 10.6834 7.31658 10.2929 7.70711L9.27175 8.72825C9.10946 8.89054 9.06923 9.13846 9.17187 9.34373C10.3585 11.7171 12.2829 13.6415 14.6563 14.8281C14.8615 14.9308 15.1095 14.8905 15.2717 14.7283L16.2929 13.7071C16.6834 13.3166 17.3166 13.3166 17.7071 13.7071Z"
                                  stroke="currentColor" stroke-width="2" />
                          </svg>
                      </span>
                      <h3 class="cdma-support-card__title">Круглосуточно</h3>
                  </div>
                  <p class="cdma-support-card__value">077</p>
                  <p class="cdma-support-card__note">Бесплатно с любого номера Perfectum</p>
              </li>
              <li class="cdma-support-card">
                  <div class="cdma-support-card__head">
                      <span class="cdma-support-card__icon" aria-hidden="true">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                              fill="none">
                              <path
                                  d="M17.7071 13.7071L20.3552 16.3552C20.7113 16.7113 20.7113 17.2887 20.3552 17.6448C18.43 19.57 15.3821 19.7866 13.204 18.153L11.6286 16.9714C9.88504 15.6638 8.33622 14.115 7.02857 12.3714L5.84701 10.796C4.21341 8.61788 4.43001 5.56999 6.35523 3.64477C6.71133 3.28867 7.28867 3.28867 7.64477 3.64477L10.2929 6.29289C10.6834 6.68342 10.6834 7.31658 10.2929 7.70711L9.27175 8.72825C9.10946 8.89054 9.06923 9.13846 9.17187 9.34373C10.3585 11.7171 12.2829 13.6415 14.6563 14.8281C14.8615 14.9308 15.1095 14.8905 15.2717 14.7283L16.2929 13.7071C16.6834 13.3166 17.3166 13.3166 17.7071 13.7071Z"
                                  stroke="currentColor" stroke-width="2" />
                          </svg>
                      </span>
                      <h3 class="cdma-support-card__title">С других номеров</h3>
                  </div>
                  <p class="cdma-support-card__value">+998 98 127 0077</p>
                  <p class="cdma-support-card__note">Стандартная тарификация</p>
              </li>
              <li class="cdma-support-card">
                  <div class="cdma-support-card__head">
                      <span class="cdma-support-card__icon" aria-hidden="true">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                              fill="none">
                              <path
                                  d="M8.39893 8.39844H15.5989M8.39893 13.1984H12.5989M21.5989 11.9984C21.5989 13.3785 21.3077 14.6905 20.7834 15.8764L21.6007 21.5975L16.6978 20.3718C15.3089 21.1529 13.7059 21.5984 11.9989 21.5984C6.69699 21.5984 2.39893 17.3004 2.39893 11.9984C2.39893 6.6965 6.69699 2.39844 11.9989 2.39844C17.3009 2.39844 21.5989 6.6965 21.5989 11.9984Z"
                                  stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                  stroke-linejoin="round" />
                          </svg>
                      </span>
                      <h3 class="cdma-support-card__title">Чат в Telegram</h3>
                  </div>
                  <p class="cdma-support-card__value">@Perfectum_Support</p>
                  <p class="cdma-support-card__note">Ответ в течение 15 минут</p>
              </li>
              <li class="cdma-support-card">
                  <div class="cdma-support-card__head">
                      <span class="cdma-support-card__icon" aria-hidden="true">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                              fill="none">
                              <g clip-path="url(#clip0_1525_1618)">
                                  <path
                                      d="M11.9582 24.0065L11.2611 23.409C10.3001 22.6044 1.90918 15.3576 1.90918 10.0568C1.90918 4.50692 6.40829 0.0078125 11.9582 0.0078125C17.5081 0.0078125 22.0072 4.50692 22.0072 10.0568C22.0072 15.3577 13.6163 22.6044 12.6593 23.413L11.9582 24.0065ZM11.9582 2.1807C7.6104 2.18563 4.08704 5.70898 4.08212 10.0568C4.08212 13.3869 9.24455 18.7066 11.9582 21.1415C14.6719 18.7056 19.8343 13.3828 19.8343 10.0568C19.8294 5.70898 16.306 2.18567 11.9582 2.1807Z"
                                      fill="currentColor" />
                                  <path
                                      d="M11.958 14.0409C9.75802 14.0409 7.97461 12.2575 7.97461 10.0576C7.97461 7.85762 9.75802 6.07422 11.958 6.07422C14.1579 6.07422 15.9413 7.85762 15.9413 10.0576C15.9413 12.2575 14.1579 14.0409 11.958 14.0409ZM11.958 8.06584C10.858 8.06584 9.96628 8.95755 9.96628 10.0575C9.96628 11.1575 10.858 12.0492 11.958 12.0492C13.0579 12.0492 13.9496 11.1575 13.9496 10.0575C13.9496 8.95755 13.058 8.06584 11.958 8.06584Z"
                                      fill="currentColor" />
                              </g>
                              <defs>
                                  <clipPath id="clip0_1525_1618">
                                      <rect width="24" height="24" fill="white" />
                                  </clipPath>
                              </defs>
                          </svg>
                      </span>
                      <h3 class="cdma-support-card__title">Офисы обслуживания</h3>
                  </div>
                  <p class="cdma-support-card__value"><NuxtLink class="cdma-support-card__link"
                          :to="localePath('/offices')">Найти ближайший →</NuxtLink></p>
                  <p class="cdma-support-card__note">18 офисов + 987 дилеров</p>
              </li>
          </ul>
      </div>
  </section>

  <!-- CDMA CTA -->
  <section class="cdma-cta">
      <div class="container">
          <span class="cdma-cta__kicker">Когда будете готовы</span>
          <h2 class="cdma-cta__title">Готовы к 5G?</h2>
          <p class="cdma-cta__text">Скорости до 1 Гбит/с, VoNR-звонки, eSIM и домашний интернет без
              проводов — всё, чего нет на CDMA.</p>
          <div class="cdma-cta__actions">
              <NuxtLink class="cdma-cta__btn cdma-cta__btn_primary" :to="localePath('/')">Узнать о 5G</NuxtLink>
              <NuxtLink class="cdma-cta__btn cdma-cta__btn_ghost" :to="localePath('/coverage-area')">Проверить покрытие</NuxtLink>
          </div>
      </div>
  </section>
</template>
