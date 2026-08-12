<script setup lang="ts">
import type { ApiResponse, PageContent } from '~/types/api'

const route = useRoute()
const localePath = useLocalePath()
const { locale } = useI18n()
const { $api } = useNuxtApp()
const t = useT()

const slug = computed(() => String(route.params.slug))

const { data: page } = await useAsyncData(
  () => `page:${slug.value}`,
  () => $api<ApiResponse<PageContent>>(`/pages/${slug.value}`).then(response => response.data),
  { watch: [locale, slug] },
)

if (!page.value) {
  throw createError({ statusCode: 404, statusMessage: 'Page Not Found', fatal: true })
}

useSeo({
  title: () => page.value?.seo.title,
  description: () => page.value?.seo.description,
  keywords: () => page.value?.seo.keywords,
  robots: () => page.value?.seo.robots,
  ogImage: () => page.value?.seo.og_image ?? undefined,
})
</script>

<template>
  <section v-if="page" class="article">
    <div class="container">
      <div class="article__inner">
        <NuxtLink class="article__back" :to="localePath('/')">
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M19 12H5M11 18l-6-6 6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          {{ t('common.back_home') }}
        </NuxtLink>

        <div class="article__hero">
          <h1 class="article__hero-title">{{ page.title }}</h1>
        </div>

        <div class="article__card">
          <img v-if="page.image" class="article__image" :src="page.image" :alt="page.title">

          <div class="article__content" v-html="page.content" />
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.article__image {
  width: 100%;
  height: auto;
  border-radius: var(--radius);
  margin-bottom: 28px;
}

.article__content :deep(p) {
  font-size: 17px;
  line-height: 1.6;
  color: rgba(0, 0, 0, 0.75);
}

.article__content :deep(p + p) {
  margin-top: 16px;
}

.article__content :deep(h2),
.article__content :deep(h3) {
  margin: 28px 0 16px;
  font-size: 18px;
  font-weight: 700;
  color: var(--color-black);
}

.article__content :deep(ul),
.article__content :deep(ol) {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin: 16px 0;
}

.article__content :deep(li) {
  position: relative;
  padding-left: 22px;
  font-size: 16px;
  line-height: 1.5;
  color: rgba(0, 0, 0, 0.7);
}

.article__content :deep(ul li)::before {
  content: "";
  position: absolute;
  left: 4px;
  top: 9px;
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: var(--color-black);
}

.article__content :deep(ol) {
  counter-reset: item;
}

.article__content :deep(ol li) {
  counter-increment: item;
}

.article__content :deep(ol li)::before {
  content: counter(item) ".";
  position: absolute;
  left: 0;
  color: var(--color-red);
}

.article__content :deep(a) {
  color: var(--color-red);
  text-decoration: underline;
}
</style>
