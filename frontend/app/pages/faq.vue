<script setup lang="ts">
const localePath = useLocalePath()
const t = useT()

useSeo({ page: 'faq', titleKey: 'seo.faq' })

const { data } = await useFaqs({ page: 'faq', withCategories: true })

const category = ref<number | ''>('')
const search = ref('')

const faqs = computed(() => data.value?.faqs ?? [])
const categories = computed(() => data.value?.categories ?? [])

function inCategory(id: number | '') {
  return id ? faqs.value.filter(item => item.category?.id === id) : faqs.value
}

const visible = computed(() => {
  const query = search.value.trim().toLowerCase()

  return inCategory(category.value).filter(item => (
    !query
    || item.question.toLowerCase().includes(query)
    || item.answer.toLowerCase().includes(query)
  ))
})

const heading = computed(() => (
  categories.value.find(item => item.id === category.value)?.name ?? t('faq.all_questions')
))
</script>

<template>
  <section class="page-hero page-hero_inner page-hero_faq">
    <div class="container">
      <div class="page-hero__inner">
        <nav class="page-hero__crumbs" :aria-label="t('common.breadcrumbs')">
          <NuxtLink class="page-hero__crumb" :to="localePath('/')">{{ t('common.home') }}</NuxtLink>
          <svg class="page-hero__crumb-sep" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">{{ t('seo.faq') }}</span>
        </nav>
        <p class="page-hero__eyebrow page-hero__eyebrow_silver">{{ t('faq.eyebrow') }}</p>
        <h1 class="page-hero__title section__title" v-html="rich(t('faq.title'), { accent: 'page-hero__title-red' })"></h1>
        <p class="page-hero__subtitle">{{ t('faq.subtitle') }}</p>
      </div>
    </div>
  </section>

  <section class="faq">
    <div class="container">
      <div class="filter-search">
        <SearchField
          v-model="search"
          :placeholder="t('faq.search_placeholder')"
          :label="t('faq.search_label')"
        />

        <div class="filter-search__chips" role="tablist" :aria-label="t('faq.categories_label')">
          <button
            type="button"
            class="filter-search__chip"
            :class="!category && 'filter-search__chip_active'"
            role="tab"
            :aria-selected="!category"
            @click="category = ''"
          >{{ t('faq.all') }} <span class="filter-search__chip-count">{{ faqs.length }}</span></button>

          <button
            v-for="item in categories"
            :key="item.id"
            type="button"
            class="filter-search__chip"
            :class="item.id === category && 'filter-search__chip_active'"
            role="tab"
            :aria-selected="item.id === category"
            @click="category = item.id"
          >{{ item.name }} <span class="filter-search__chip-count">{{ inCategory(item.id).length }}</span></button>
        </div>
      </div>

      <div class="section-head">
        <h2 class="section-head__title">{{ heading }}</h2>
        <span class="section-head__count">{{ visible.length }}</span>
      </div>

      <FaqAccordion v-if="visible.length" :items="visible" />
      <p v-else class="faq__empty">{{ t('faq.empty') }}</p>

      <div class="support-cta">
        <h2 class="support-cta__title">{{ t('faq.cta_title') }}</h2>
        <NuxtLink class="support-cta__btn" :to="localePath('/help/contact')">{{ t('faq.cta_button') }}</NuxtLink>
      </div>
    </div>
  </section>
</template>
