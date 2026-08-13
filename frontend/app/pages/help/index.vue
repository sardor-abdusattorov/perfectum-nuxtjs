<script setup lang="ts">
const localePath = useLocalePath()
const t = useT()

useSeo({ page: 'help', titleKey: 'seo.help' })

const { data } = await useFaqs({ page: 'help' })

const faqs = computed(() => data.value?.faqs ?? [])
</script>

<template>
  <section class="page-hero page-hero_inner page-hero_help">
    <div class="container">
      <div class="page-hero__inner">
        <nav class="page-hero__crumbs" :aria-label="t('common.breadcrumbs')">
          <NuxtLink class="page-hero__crumb" :to="localePath('/')">{{ t('common.home') }}</NuxtLink>
          <svg class="page-hero__crumb-sep" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">{{ t('seo.help') }}</span>
        </nav>
        <h1 class="page-hero__title section__title" v-html="rich(t('help.title'), { accent: 'page-hero__title-red' })"></h1>
      </div>
    </div>
  </section>

  <section class="help">
    <div class="container">
      <div class="help__panel">
        <HelpAside active="faq" />

        <div class="help__content">
          <div class="help__tab help__tab_active">
            <h2 class="help__tab-title">{{ t('help.nav_faq') }}</h2>

            <FaqAccordion v-if="faqs.length" :items="faqs" open-first />
            <p v-else class="faq__empty">{{ t('faq.empty') }}</p>

            <NuxtLink class="help__all-link arrow-link" :to="localePath('/faq')">
              {{ t('help.all_questions') }}
              <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </NuxtLink>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
