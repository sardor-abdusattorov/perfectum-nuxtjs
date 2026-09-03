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

const back = computed(() => {
  const parent = page.value?.parent

  return parent
    ? { to: localePath(`/pages/${parent.slug}`), label: parent.title }
    : { to: localePath('/'), label: t('common.back_home') }
})

useSeo({
  title: () => page.value?.seo.title,
  description: () => page.value?.seo.description,
  keywords: () => page.value?.seo.keywords,
  robots: () => page.value?.seo.robots,
  ogImage: () => page.value?.seo.og_image ?? undefined,
})
</script>

<template>
  <template v-if="page">
    <!-- Группа: заголовок раздела и сетка карточек, как на прежнем сайте.
         Ни чёрной шапки, ни белой плашки с текстом — их тут нечем наполнить. -->
    <section v-if="page.is_group" class="page-group">
      <div class="container">
        <h1 class="page-group__title">{{ page.title }}</h1>

        <ul v-if="page.cards.length" class="page-group__grid">
          <li v-for="card in page.cards" :key="card.slug" class="page-group__card">
            <NuxtLink class="page-group__link" :to="localePath(`/pages/${card.slug}`)">
              <h2 class="page-group__card-title">{{ card.title }}</h2>
              <p v-if="card.text" class="page-group__card-text">{{ card.text }}</p>
            </NuxtLink>
          </li>
        </ul>
      </div>
    </section>

    <section v-else class="article">
      <div class="container">
        <div class="article__inner">
          <NuxtLink class="article__back" :to="back.to">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M19 12H5M11 18l-6-6 6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            {{ back.label }}
          </NuxtLink>

          <div class="article__hero">
            <h1 class="article__hero-title">{{ page.title }}</h1>
          </div>

          <div v-if="page.image || page.content" class="article__card">
            <img v-if="page.image" class="article__image" :src="page.image" :alt="page.title">

            <div v-if="page.content" class="article__content rich" v-html="page.content" />
          </div>
        </div>
      </div>
    </section>
  </template>
</template>

<style scoped>
/* Размеры взяты с прежнего сайта: сетка в три колонки по 20px,
   карточка со скруглением 16, подпись обрезается на третьей строке. */
.page-group {
  padding: 48px 0 72px;
  background: var(--color-gray);
}

.page-group__title {
  margin-bottom: 32px;
  font-size: 30px;
  font-weight: 700;
  line-height: 1.2;
  color: var(--color-black);
}

.page-group__grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 20px;
}

.page-group__card {
  border-radius: 16px;
  background: var(--color-white);
  transition: var(--transition);
}

.page-group__card:hover {
  box-shadow: 0 15px 30px rgba(41, 39, 88, 0.07);
}

.page-group__link {
  display: block;
  height: 100%;
  padding: 20px;
  color: inherit;
}

.page-group__card-title {
  min-height: 43px;
  font-size: 17px;
  font-weight: 600;
  line-height: 1.35;
  color: var(--color-black);
  transition: var(--transition);
}

.page-group__card:hover .page-group__card-title {
  color: var(--color-red);
}

.page-group__card-text {
  margin-top: 10px;
  min-height: 67px;
  font-size: 15px;
  line-height: 1.4;
  color: rgba(0, 0, 0, 0.6);
  display: -webkit-box;
  -webkit-line-clamp: 3;
  line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}

@media (max-width: 1024px) {
  .page-group__grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 640px) {
  .page-group {
    padding: 32px 0 48px;
  }

  .page-group__title {
    font-size: 24px;
  }

  .page-group__grid {
    grid-template-columns: minmax(0, 1fr);
  }

  .page-group__card-title,
  .page-group__card-text {
    min-height: 0;
  }
}

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
