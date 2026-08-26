<script setup lang="ts">
await useBlocks('cdma_connect')

const hero = useBlock('cdma_connect', 'page_hero')
const article = useBlock('cdma_connect', 'intro')

const localePath = useLocalePath()
const t = useT()

definePageMeta({ layout: 'cdma' })
useSeo({ page: 'cdma_connect', titleKey: 'seo.cdma_connect' })
</script>

<template>
  <section class="cdma-hero cdma-hero_slim">
    <div class="container">
      <nav class="cdma-crumbs" :aria-label="t('common.breadcrumbs')">
        <NuxtLink class="cdma-crumbs__link" :to="localePath('/cdma')">{{ t('common.home') }}</NuxtLink>
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <span class="cdma-crumbs__current" v-html="rich(hero.title)"></span>
      </nav>
      <h1 class="cdma-hero__title" v-html="rich(hero.title)"></h1>
    </div>
  </section>

  <section class="cdma-connect">
    <div class="container">
      <p v-if="article.date" class="cdma-connect__date">{{ article.date }}</p>
      <h2 v-if="article.title" class="cdma-connect__title">{{ article.title }}</h2>

      <div v-if="article.content" class="cdma-connect__article rich" v-html="article.content"></div>
    </div>
  </section>
</template>
