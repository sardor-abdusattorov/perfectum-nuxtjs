<script setup lang="ts">
const localePath = useLocalePath()
const t = useT()
const { long: dateLong } = useDates()

definePageMeta({ layout: 'cdma' })
useSeo({ titleKey: 'cdma.news_title' })

const { search, category, page } = useListQuery()

const { data } = await useNewsList({ network: 'cdma', category, search, page }, true)

const items = computed(() => data.value?.items ?? [])
const meta = computed(() => data.value?.meta ?? { current_page: 1, last_page: 1, total: 0 })
const categories = computed(() => data.value?.categories ?? [])
</script>

<template>
  <section class="cdma-hero cdma-hero_inner">
    <div class="container">
      <nav class="cdma-crumbs" :aria-label="t('common.breadcrumbs')">
        <NuxtLink class="cdma-crumbs__link" :to="localePath('/cdma')">{{ t('seo.cdma') }}</NuxtLink>
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <span class="cdma-crumbs__current">{{ t('cdma.news_title') }}</span>
      </nav>
      <h1 class="cdma-hero__title">{{ t('cdma.news_title') }}</h1>
    </div>
  </section>

  <section class="cdma-section">
    <div class="container">
      <div class="filter-search">
        <SearchField
          v-model="search"
          :placeholder="t('news.search_placeholder')"
          :label="t('news.search_label')"
        />
        <div class="filter-search__chips" role="tablist" :aria-label="t('news.categories_label')">
          <button
            type="button"
            class="filter-search__chip"
            :class="!category && 'filter-search__chip_active'"
            role="tab"
            :aria-selected="!category"
            @click="category = ''"
          >{{ t('news.all') }}</button>
          <button
            v-for="item in categories"
            :key="item.id"
            type="button"
            class="filter-search__chip"
            :class="item.slug === category && 'filter-search__chip_active'"
            role="tab"
            :aria-selected="item.slug === category"
            @click="category = item.slug ?? ''"
          >{{ item.name }}</button>
        </div>
      </div>

      <div class="cdma-section__head">
        <h2 class="cdma-section__title">{{ t('news.all_title') }}</h2>
        <span class="section-head__count">{{ meta.total }}</span>
      </div>

      <ul v-if="items.length" class="cdma-news">
        <li v-for="item in items" :key="item.slug" class="cdma-news-card">
          <span class="cdma-news-card__date">{{ dateLong(item.published_at) }}</span>
          <h3 class="cdma-news-card__title">
            <NuxtLink class="cdma-news-card__link" :to="localePath(`/cdma/news/${item.slug}`)">{{ item.title }}</NuxtLink>
          </h3>
          <span v-if="item.category" class="cdma-news-card__cat">{{ item.category.name }}</span>
        </li>
      </ul>
      <p v-else class="cdma-regions__empty">{{ t('news.empty') }}</p>

      <AppPagination :page="meta.current_page" :pages="meta.last_page" @change="page = $event" />
    </div>
  </section>
</template>
