<script setup lang="ts">
const { t } = useI18n()

useHead({
  title: t('seo.home.title'),
})
</script>

<template>
  <HomeHero />
  <HomeMarquee />
  <HomeChoose />
  <HomeTariffs />
  <HomeFeatures />
  <HomeCoverage />
  <HomeAppPromo />
</template>
