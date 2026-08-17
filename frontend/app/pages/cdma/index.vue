<script setup lang="ts">
const localePath = useLocalePath()
const t = useT()

definePageMeta({ layout: 'cdma' })
useSeo({ page: 'cdma', titleKey: 'seo.cdma' })

await useBlocks('cdma')

const hero = useBlock('cdma', 'page_hero')
const support = useBlock('cdma', 'support')
const cta = useBlock('cdma', 'cta')

const supportCards = computed(() => published(support.value.cards))

/**
 * The page draws six independent sections, so they are fetched together: one
 * round-trip of depth instead of six waiting on each other.
 */
const [
  { data: faqData },
  { data: newsData },
  { data: actionsData },
  { data: serviceCatalog },
  { data: tariffCatalog },
] = await Promise.all([
  useFaqs({ page: 'cdma' }),
  useNewsList({ network: 'cdma', perPage: 24 }),
  useActionsList({ network: 'cdma', perPage: 12 }),
  useServiceCatalog('cdma'),
  useTariffCatalog(),
])

const tariffType = ref<number | ''>('')

const cdmaTariffs = computed(() => {
  const category = tariffCatalog.value?.categories.find(item => item.network === 'cdma')

  return (tariffCatalog.value?.tariffs ?? []).filter(tariff => tariff.category?.id === category?.id)
})

const tariffChips = computed(() => (
  (tariffCatalog.value?.types ?? []).filter(type => cdmaTariffs.value.some(tariff => tariff.type?.id === type.id))
))

const railTariffs = computed(() => (
  tariffType.value ? cdmaTariffs.value.filter(tariff => tariff.type?.id === tariffType.value) : cdmaTariffs.value
))

const serviceCategory = ref<number | ''>('')

const serviceChips = computed(() => {
  const services = serviceCatalog.value?.services ?? []

  return (serviceCatalog.value?.categories ?? [])
    .filter(item => services.some(service => service.category?.id === item.id))
})

const cdmaServices = computed(() => {
  const services = serviceCatalog.value?.services ?? []

  return serviceCategory.value
    ? services.filter(service => service.category?.id === serviceCategory.value)
    : services
})

const faqs = computed(() => faqData.value?.faqs ?? [])
const { locale } = useI18n()
const { long: dateLong, monthName } = useDates()

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

useSlider(tariffRail, { ...RAIL_OPTIONS, scrollbar: { el: '#cdma-tariffs .cdma-rail__bar', draggable: true } }, () => `${locale.value}:${tariffType.value}`)
useSlider(serviceRail, { ...RAIL_OPTIONS, scrollbar: { el: '#cdma-services .cdma-rail__bar', draggable: true } }, () => `${locale.value}:${serviceCategory.value}`)
</script>


<template>
  <!-- CDMA HERO -->
  <section class="cdma-hero">
      <div class="container">
          <h1 class="cdma-hero__title">{{ hero.title }}</h1>
          <p v-if="hero.subtitle" class="cdma-hero__subtitle">{{ hero.subtitle }}</p>
      </div>
  </section>

  <!-- CDMA TABS -->
  <nav class="cdma-tabs" :aria-label="t('cdma.sections_label')">
      <div class="container">
          <ul class="cdma-tabs__list">
              <li class="cdma-tabs__item">
                  <a class="cdma-tabs__link cdma-tabs__link_active" href="#cdma-tariffs">{{ t('cdma.tariffs_title') }}</a>
              </li>
              <li class="cdma-tabs__item">
                  <a class="cdma-tabs__link" href="#cdma-services">{{ t('cdma.services_title') }}</a>
              </li>
              <li class="cdma-tabs__item">
                  <a class="cdma-tabs__link" href="#cdma-numbers">{{ t('cdma.tab_numbers') }}</a>
              </li>
              <li class="cdma-tabs__item">
                  <a class="cdma-tabs__link" href="#cdma-faq">FAQ</a>
              </li>
              <li class="cdma-tabs__item">
                  <a class="cdma-tabs__link" href="#cdma-support">{{ t('cdma.support_title') }}</a>
              </li>
              <li class="cdma-tabs__item">
                  <a class="cdma-tabs__link" href="#cdma-news">{{ t('cdma.news_title') }}</a>
              </li>
              <li class="cdma-tabs__item">
                  <a class="cdma-tabs__link" href="#cdma-promo">{{ t('cdma.promo_title') }}</a>
              </li>
              <li class="cdma-tabs__item">
                  <NuxtLink class="cdma-tabs__link" :to="localePath('/cdma/dealers')">{{ t('cdma.tab_dealers') }}</NuxtLink>
              </li>
          </ul>
          <NuxtLink class="cdma-tabs__connect" :to="localePath('/cdma/connect')">{{ t('cdma.connect') }}</NuxtLink>
      </div>
  </nav>

  <!-- CDMA TARIFFS -->
  <section class="cdma-section" id="cdma-tariffs">
      <div class="container">
          <h2 class="cdma-section__title">{{ t('cdma.tariffs_title') }}</h2>
          <ul class="cdma-chips">
              <li class="cdma-chips__item">
                  <button type="button" class="cdma-chips__btn"
                      :class="!tariffType && 'cdma-chips__btn_active'"
                      @click="tariffType = ''">{{ t('tariffs.all') }}</button>
              </li>
              <li v-for="chip in tariffChips" :key="chip.id" class="cdma-chips__item">
                  <button type="button" class="cdma-chips__btn"
                      :class="chip.id === tariffType && 'cdma-chips__btn_active'"
                      @click="tariffType = chip.id">{{ chip.name }}</button>
              </li>
          </ul>

          <div ref="tariffRail" class="cdma-rail swiper">
              <ul class="cdma-rail__track swiper-wrapper">
                  <li v-for="tariff in railTariffs" :key="tariff.slug"
                      class="cdma-tariff-card swiper-slide">
                      <span v-if="tariff.type" class="cdma-tariff-card__badge">{{ tariff.type.name }}</span>
                      <h3 class="cdma-tariff-card__name">{{ tariff.name }}</h3>
                      <p class="cdma-tariff-card__price">{{ tariff.price }}<span
                              class="cdma-tariff-card__period">{{ tariff.price_currency }} /
                              {{ tariff.price_period }}</span></p>
                      <ul class="cdma-tariff-card__list">
                          <li v-for="(feature, index) in tariff.features.slice(0, 3)" :key="index"
                              class="cdma-tariff-card__feature">{{ feature.note ? `${feature.title} ${feature.note}` : feature.title }}</li>
                      </ul>
                      <NuxtLink class="cdma-tariff-card__more"
                          :to="localePath(`/cdma/tariffs/${tariff.slug}`)">{{ t('cdma.more') }}</NuxtLink>
                  </li>
              </ul>
              <div class="cdma-rail__bar swiper-scrollbar"></div>
          </div>
      </div>
  </section>

  <!-- CDMA SERVICES -->
  <section class="cdma-section" id="cdma-services">
      <div class="container">
          <h2 class="cdma-section__title">{{ t('cdma.services_title') }}</h2>
          <ul class="cdma-chips">
              <li class="cdma-chips__item">
                  <button type="button" class="cdma-chips__btn"
                      :class="!serviceCategory && 'cdma-chips__btn_active'"
                      @click="serviceCategory = ''">{{ t('tariffs.all') }}</button>
              </li>
              <li v-for="chip in serviceChips" :key="chip.id" class="cdma-chips__item">
                  <button type="button" class="cdma-chips__btn"
                      :class="chip.id === serviceCategory && 'cdma-chips__btn_active'"
                      @click="serviceCategory = chip.id">{{ chip.name }}</button>
              </li>
          </ul>

          <div ref="serviceRail" class="cdma-rail swiper">
              <ul class="cdma-rail__track swiper-wrapper">
                  <li v-for="service in cdmaServices" :key="service.slug"
                      class="cdma-service-card swiper-slide"><NuxtLink class="cdma-service-card__link"
                          :to="localePath(`/cdma/services/${service.slug}`)">
                          <div class="cdma-service-card__head">
                              <span class="cdma-service-card__icon" aria-hidden="true">
                                  <ServiceIcon :icon="service.icon" />
                              </span>
                              <h3 class="cdma-service-card__name">{{ service.name }}</h3>
                          </div>
                          <p v-if="service.excerpt" class="cdma-service-card__text">{{ service.excerpt }}</p>
                          <span v-if="service.ussd" class="cdma-service-card__code">{{ service.ussd }}</span>
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
          <h2 class="cdma-section__title">{{ t('cdma.numbers_title') }}</h2>
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
                  <span class="cdma-news-card__date">{{ dateLong(item.published_at) }}</span>
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
                      <span class="cdma-promo-card__date">{{ dateLong(item.starts_at) }}</span>
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
          <h2 class="cdma-section__title">{{ t('cdma.support_title') }}</h2>
          <ul v-if="supportCards.length" class="cdma-support">
              <li v-for="(card, index) in supportCards" :key="index" class="cdma-support-card">
                  <div class="cdma-support-card__head">
                      <span v-if="card.icon_svg" class="cdma-support-card__icon" aria-hidden="true" v-html="card.icon_svg"></span>
                      <h3 class="cdma-support-card__title">{{ card.title }}</h3>
                  </div>
                  <p class="cdma-support-card__value">
                      <NuxtLink v-if="card.url" class="cdma-support-card__link" :to="localePath(card.url)">{{ card.value }}</NuxtLink>
                      <template v-else>{{ card.value }}</template>
                  </p>
                  <p v-if="card.note" class="cdma-support-card__note">{{ card.note }}</p>
              </li>
          </ul>
      </div>
  </section>

  <!-- CDMA CTA -->
  <section v-if="cta.title" class="cdma-cta">
      <div class="container">
          <span v-if="cta.kicker" class="cdma-cta__kicker">{{ cta.kicker }}</span>
          <h2 class="cdma-cta__title">{{ cta.title }}</h2>
          <p v-if="cta.text" class="cdma-cta__text">{{ cta.text }}</p>
          <div class="cdma-cta__actions">
              <NuxtLink v-if="cta.primary_label" class="cdma-cta__btn cdma-cta__btn_primary" :to="localePath(cta.primary_url || '/')">{{ cta.primary_label }}</NuxtLink>
              <NuxtLink v-if="cta.ghost_label" class="cdma-cta__btn cdma-cta__btn_ghost" :to="localePath(cta.ghost_url || '/')">{{ cta.ghost_label }}</NuxtLink>
          </div>
      </div>
  </section>
</template>
