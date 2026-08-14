<script setup lang="ts">
const localePath = useLocalePath()
const t = useT()

useSeo({ page: 'careers', titleKey: 'seo.careers' })

const page = ref(1)
const { data } = await useVacanciesList({ page })

const items = computed(() => data.value?.items ?? [])
const meta = computed(() => data.value?.meta ?? { current_page: 1, last_page: 1, total: 0 })
</script>

<template>
  <section class="page-hero page-hero_inner page-hero_company">
    <div class="container">
      <div class="page-hero__inner">
        <nav class="page-hero__crumbs" :aria-label="t('common.breadcrumbs')">
          <NuxtLink class="page-hero__crumb" :to="localePath('/')">{{ t('common.home') }}</NuxtLink>
          <svg class="page-hero__crumb-sep" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">{{ t('seo.careers') }}</span>
        </nav>
        <p class="page-hero__eyebrow page-hero__eyebrow_silver">{{ t('careers.eyebrow') }}</p>
        <h1 class="page-hero__title section__title" v-html="rich(t('careers.title'), { accent: 'page-hero__title-red' })"></h1>
        <p class="page-hero__subtitle">{{ t('careers.subtitle') }}</p>
      </div>
    </div>
  </section>

  <section class="company">
    <div class="container">
      <CompanyNav active="careers" />

      <div class="careers-intro">
        <h2 class="careers-intro__title">{{ t('careers.intro_title') }}</h2>
        <p class="careers-intro__text">{{ t('careers.intro_text') }}</p>
      </div>

      <ul v-if="items.length" class="vac-list">
        <li v-for="item in items" :key="item.slug">
          <NuxtLink class="vac-row" :to="localePath(`/careers/${item.slug}`)">
            <span class="vac-row__body">
              <h2 class="vac-row__title">{{ item.title }}</h2>
              <span class="vac-row__meta">
                <span v-if="item.department" class="vac-row__tag">{{ item.department }}</span>
                <span v-if="item.city" class="vac-row__tag">{{ item.city }}</span>
                <span v-if="item.employment" class="vac-row__tag">{{ item.employment }}</span>
              </span>
            </span>
            <span class="vac-row__arrow" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </span>
          </NuxtLink>
        </li>
      </ul>
      <p v-else class="company__empty">{{ t('careers.empty') }}</p>

      <AppPagination :page="meta.current_page" :pages="meta.last_page" @change="page = $event" />
    </div>
  </section>
</template>
