<script setup lang="ts">
const props = defineProps<{
  title: string
  variant?: string
  crumb?: string
  eyebrow?: string
  eyebrowSilver?: boolean
  subtitle?: string
  plainTitle?: boolean
  watermark?: string
}>()

const localePath = useLocalePath()
const t = useT()

const titleClass = computed(() => (props.plainTitle ? 'page-hero__title' : 'page-hero__title section__title'))
</script>

<template>
  <section class="page-hero" :class="variant">
    <div class="container">
      <div class="page-hero__inner">
        <nav v-if="crumb" class="page-hero__crumbs" :aria-label="t('common.breadcrumbs')">
          <NuxtLink class="page-hero__crumb" :to="localePath('/')">{{ t('common.home') }}</NuxtLink>
          <svg class="page-hero__crumb-sep" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">{{ crumb }}</span>
        </nav>

        <p
          v-if="eyebrow"
          class="page-hero__eyebrow"
          :class="eyebrowSilver && 'page-hero__eyebrow_silver'"
        >{{ eyebrow }}</p>

        <h1 :class="titleClass" v-html="title"></h1>

        <div v-if="subtitle" class="page-hero__subtitle" v-html="subtitle"></div>

        <slot />
      </div>

      <span v-if="watermark" class="page-hero__watermark" aria-hidden="true">{{ watermark }}</span>
    </div>
  </section>
</template>
