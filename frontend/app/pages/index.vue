<script setup lang="ts">
await useBlocks('home')

useSeo({ page: 'home' })
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
