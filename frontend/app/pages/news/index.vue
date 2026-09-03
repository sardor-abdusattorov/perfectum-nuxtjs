<script setup lang="ts">
const localePath = useLocalePath()
const t = useT()

useSeo({ page: 'news', titleKey: 'seo.news' })

const { search, category, page } = useListQuery()

const { data } = await useNewsList({ network: '5g', category, search, page }, true)

const items = computed(() => data.value?.items ?? [])
const meta = computed(() => data.value?.meta ?? { current_page: 1, last_page: 1, total: 0 })
const categories = computed(() => data.value?.categories ?? [])

const featured = computed(() => (
  page.value === 1 && !search.value && !category.value
    ? items.value.find(item => item.is_featured) ?? null
    : null
))

const rest = computed(() => items.value.filter(item => item !== featured.value))
</script>

<template>
  <section class="page-hero page-hero_inner page-hero_news">
    <div class="container">
      <div class="page-hero__inner">
        <nav class="page-hero__crumbs" :aria-label="t('common.breadcrumbs')">
          <NuxtLink class="page-hero__crumb" :to="localePath('/')">{{ t('common.home') }}</NuxtLink>
          <svg class="page-hero__crumb-sep" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">{{ t('seo.news') }}</span>
        </nav>
        <h1 class="page-hero__title section__title" v-html="rich(t('news.title'), { accent: 'page-hero__title-red' })"></h1>
      </div>
    </div>
  </section>

  <section class="news">
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

      <article v-if="featured" class="news-feature">
        <div
          class="news-feature__media"
          :class="featured.preview_image && 'news-feature__media_photo'"
          :style="cover(featured.preview_image)"
        >
          <span v-if="featured.category" class="news-feature__cat">{{ featured.category.name }}</span>
          <h2 class="news-feature__title">{{ featured.title }}</h2>
        </div>
        <div class="news-feature__body">
          <span class="news-feature__date">{{ dateShort(featured.published_at) }}</span>
          <p class="news-feature__text">{{ featured.excerpt }}</p>
          <NuxtLink class="news-feature__link" :to="localePath(`/news/${featured.slug}`)">
            {{ t('news.read_full') }}
            <svg viewBox="0 0 20 16" fill="none" aria-hidden="true"><path d="M12 1l7 7-7 7M19 8H1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
          </NuxtLink>
        </div>
      </article>

      <div class="section-head">
        <h2 class="section-head__title">{{ t('news.all_title') }}</h2>
        <span class="section-head__count">{{ meta.total }}</span>
      </div>

      <ul v-if="rest.length" class="news-grid">
        <li v-for="item in rest" :key="item.id" class="news-card">
          <NuxtLink class="news-card__link" :to="localePath(`/news/${item.slug}`)">
            <div
              class="news-card__media"
              :class="item.preview_image && 'news-card__media_photo'"
              :style="cover(item.preview_image)"
            >
              <span v-if="item.category" class="news-card__cat">{{ item.category.name }}</span>
            </div>
            <div class="news-card__body">
              <h3 class="news-card__title">{{ item.title }}</h3>
              <p class="news-card__text">{{ item.excerpt }}</p>
              <div class="news-card__foot">
                <span class="news-card__date">{{ dateShort(item.published_at) }}</span>
                <span class="news-card__more">
                  {{ t('news.read_full') }}
                  <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
                </span>
              </div>
            </div>
          </NuxtLink>
        </li>
      </ul>
      <p v-else-if="!featured" class="news__empty">{{ t('news.empty') }}</p>

      <AppPagination :page="meta.current_page" :pages="meta.last_page" @change="page = $event" />
    </div>
  </section>
</template>
