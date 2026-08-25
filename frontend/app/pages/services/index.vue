<script setup lang="ts">
const localePath = useLocalePath()
const t = useT()

useSeo({ page: 'services', titleKey: 'seo.services' })

const { data: catalog } = await useServiceCatalog('5g')

const query = ref('')

/**
 * The open category lives in the address as its slug, so the state of the
 * switch is a link anyone can send; «все» is the bare page. The address only
 * seeds the ref — from then on the switch owns it and writes itself back.
 */
const route = useRoute()
const router = useRouter()

const category = ref<number | ''>(
  (catalog.value?.categories ?? []).find(item => item.slug === route.query.category)?.id ?? '',
)

watch(category, () => {
  const slug = (catalog.value?.categories ?? []).find(item => item.id === category.value)?.slug

  if ((slug ?? undefined) !== route.query.category) {
    router.replace({ query: { ...route.query, category: slug } })
  }
})

const found = computed(() => {
  const needle = query.value.trim().toLowerCase()
  let list = catalog.value?.services ?? []

  if (category.value) {
    list = list.filter(service => service.category?.id === category.value)
  }

  if (needle) {
    list = list.filter(service => `${service.name} ${service.excerpt ?? ''}`.toLowerCase().includes(needle))
  }

  return list
})

const groups = computed(() => (
  (catalog.value?.categories ?? [])
    .map(item => ({ category: item, services: found.value.filter(service => service.category?.id === item.id) }))
    .filter(group => group.services.length)
))

const tabs = computed(() => {
  const services = catalog.value?.services ?? []

  return (catalog.value?.categories ?? [])
    .map(item => ({ id: item.id, name: item.name, count: services.filter(service => service.category?.id === item.id).length }))
    .filter(tab => tab.count)
})

const total = computed(() => catalog.value?.services.length ?? 0)

function countLabel(count: number): string {
  return t('services.count').replace('{n}', String(count))
}
</script>

<template>
  <!-- PAGE HERO -->
  <section class="page-hero page-hero_inner page-hero_services">
      <div class="container">
          <div class="page-hero__inner">
              <nav class="page-hero__crumbs" :aria-label="t('common.breadcrumbs')">
                  <NuxtLink class="page-hero__crumb" :to="localePath('/')">{{ t('common.home') }}</NuxtLink>
                  <svg class="page-hero__crumb-sep" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                      fill="none" aria-hidden="true">
                      <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">{{ t('seo.services') }}</span>
              </nav>
              <p class="page-hero__eyebrow page-hero__eyebrow_silver">{{ t('services.hero_eyebrow') }}</p>
              <h1 class="page-hero__title section__title" v-html="t('services.hero_title')"></h1>
              <p class="page-hero__subtitle">{{ t('services.hero_subtitle') }}</p>
          </div>
      </div>
  </section>

  <!-- SERVICES -->
  <section class="services">
      <div class="container">
          <div class="filter-search">
              <h2 class="filter-search__heading">{{ t('services.catalog_heading') }}</h2>
              <form class="filter-search__field" role="search" @submit.prevent>
                  <input v-model="query" type="search" class="filter-search__input"
                      :placeholder="t('services.search_placeholder')"
                      :aria-label="t('services.search_label')" />
                  <button type="submit" class="filter-search__btn" :aria-label="t('services.search_label')">
                      <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                          <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8" />
                          <path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="1.8"
                              stroke-linecap="round" />
                      </svg>
                  </button>
              </form>
              <div class="filter-search__chips" role="tablist" :aria-label="t('tariffs.categories_label')">
                  <button type="button" class="filter-search__chip"
                      :class="!category && 'filter-search__chip_active'" role="tab"
                      :aria-selected="!category" @click="category = ''">{{ t('tariffs.all') }} <span
                          class="filter-search__chip-count">{{ total }}</span></button>
                  <button v-for="tab in tabs" :key="tab.id" type="button" class="filter-search__chip"
                      :class="tab.id === category && 'filter-search__chip_active'" role="tab"
                      :aria-selected="tab.id === category" @click="category = tab.id">{{ tab.name }} <span
                          class="filter-search__chip-count">{{ tab.count }}</span></button>
              </div>
          </div>

          <template v-for="group in groups" :key="group.category.id">
              <div class="section-head">
                  <h2 class="section-head__title section-head__title_upper">{{ group.category.name }}</h2>
                  <span class="section-head__count">{{ countLabel(group.services.length) }}</span>
              </div>

              <ul class="services__grid">
                  <li v-for="service in group.services" :key="service.slug" class="service-card">
                      <NuxtLink class="service-card__link" :to="localePath(`/services/${service.slug}`)">
                          <span class="service-card__icon">
                              <ServiceIcon :icon="service.icon" />
                          </span>
                          <h3 class="service-card__title">{{ service.name }}</h3>
                          <p v-if="service.excerpt" class="service-card__text">{{ service.excerpt }}</p>
                          <div class="service-card__foot">
                              <span class="service-card__cat">{{ group.category.name }}</span>
                              <span class="service-card__arrow" aria-hidden="true">
                                  <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                      aria-hidden="true">
                                      <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                                          stroke-linecap="round" stroke-linejoin="round" />
                                  </svg>
                              </span>
                          </div>
                      </NuxtLink>
                  </li>
              </ul>
          </template>

          <p v-if="!groups.length" class="devices__note">{{ t('services.empty') }}</p>

          <div class="support-cta">
              <h2 class="support-cta__title">{{ t('services.support_title') }}</h2>
              <NuxtLink class="support-cta__btn" :to="localePath('/help/contact')">{{ t('services.support_btn') }}</NuxtLink>
          </div>
      </div>
  </section>
</template>
