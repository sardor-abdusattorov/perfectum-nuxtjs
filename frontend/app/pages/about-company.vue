<script setup lang="ts">
await useBlocks('about_company')

const hero = useBlock('about_company', 'page_hero')
const stats = useBlock('about_company', 'stats')
const intro = useBlock('about_company', 'intro')
const timeline = useBlock('about_company', 'timeline')

const t = useT()

useSeo({ page: 'about_company', titleKey: 'seo.about' })

const statItems = computed(() => published(stats.value.items))
const timelineItems = computed(() => published(timeline.value.items))
</script>

<template>
  <PageHero
    variant="page-hero_inner page-hero_company"
    :crumb="t('seo.about')"
    :eyebrow="hero.eyebrow"
    eyebrow-silver
    :title="rich(hero.title, { accent: 'page-hero__title-red' })"
    :subtitle="rich(hero.subtitle)"
  />

  <section class="company">
    <div class="container">
      <CompanyNav active="about" />

      <ul v-if="statItems.length" class="about-stats">
        <li v-for="(item, index) in statItems" :key="index" class="about-stat">
          <div class="about-stat__value">{{ item.value }}</div>
          <div class="about-stat__label">{{ item.label }}</div>
        </li>
      </ul>

      <div v-if="intro.content" class="about-intro rich" v-html="intro.content"></div>

      <ul v-if="timelineItems.length" class="timeline">
        <li v-for="(item, index) in timelineItems" :key="index" class="timeline__item">
          <div class="timeline__year">{{ item.year }}</div>
          <p class="timeline__text">{{ item.text }}</p>
        </li>
      </ul>
    </div>
  </section>
</template>
