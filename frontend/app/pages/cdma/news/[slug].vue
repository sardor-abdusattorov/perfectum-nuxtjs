<script setup lang="ts">
const route = useRoute()
const localePath = useLocalePath()
const t = useT()
const { long: dateLong } = useDates()

definePageMeta({ layout: 'cdma' })

const slug = computed(() => String(route.params.slug ?? ''))
const { data: item } = await useNewsItem(slug)

if (!item.value) {
  throw createError({ statusCode: 404, statusMessage: 'Not Found', fatal: true })
}

useSeo({ title: () => item.value?.title ?? '' })
</script>

<template>
  <template v-if="item">
    <section class="cdma-hero cdma-hero_slim">
      <div class="container">
        <nav class="cdma-crumbs" :aria-label="t('common.breadcrumbs')">
          <NuxtLink class="cdma-crumbs__link" :to="localePath('/cdma')">{{ t('seo.cdma') }}</NuxtLink>
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <NuxtLink class="cdma-crumbs__link" :to="localePath('/cdma') + '#cdma-news'">{{ t('cdma.news_title') }}</NuxtLink>
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <span class="cdma-crumbs__current">{{ item.title }}</span>
        </nav>
        <h1 class="cdma-hero__title">{{ item.title }}</h1>
      </div>
    </section>

    <section class="cdma-detail cdma-detail_article">
      <div class="container">
        <div class="cdma-article-wrap">
          <article class="cdma-article">
            <div class="cdma-article__meta cdma-article__meta_stacked">
              <span class="cdma-article__icon" aria-hidden="true">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                  <path d="M3.875 8.46875H19.625M5.91071 2V3.68771M17.375 2V3.6875M17.375 3.6875H6.125C4.26104 3.6875 2.75 5.19854 2.75 7.0625V18.3126C2.75 20.1766 4.26104 21.6876 6.125 21.6876H17.375C19.239 21.6876 20.75 20.1766 20.75 18.3126L20.75 7.0625C20.75 5.19854 19.239 3.6875 17.375 3.6875ZM6.6875 12.4063H16.8125M6.6875 16.9063H16.8125" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </span>
              <span class="cdma-article__date">{{ dateLong(item.published_at) }}</span>
            </div>
            <span v-if="item.category" class="cdma-badge">{{ item.category.name }}</span>
            <div class="cdma-article__body rich" v-html="item.content"></div>
          </article>
        </div>
      </div>
    </section>
  </template>
</template>
