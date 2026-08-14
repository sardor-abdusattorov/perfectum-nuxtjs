<script setup lang="ts">
const localePath = useLocalePath()
const t = useT()

useSeo({ page: 'procurement', titleKey: 'seo.procurement' })

const { page } = useListQuery({ search: false, category: false })
const { data } = await useTendersList({ page })

const items = computed(() => data.value?.items ?? [])
const meta = computed(() => data.value?.meta ?? { current_page: 1, last_page: 1, total: 0 })
</script>

<template>
  <section class="page-hero page-hero_inner page-hero_procurement">
    <div class="container">
      <div class="page-hero__inner">
        <nav class="page-hero__crumbs" :aria-label="t('common.breadcrumbs')">
          <NuxtLink class="page-hero__crumb" :to="localePath('/')">{{ t('common.home') }}</NuxtLink>
          <svg class="page-hero__crumb-sep" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">{{ t('seo.procurement') }}</span>
        </nav>
        <h1 class="page-hero__title section__title">{{ t('seo.procurement') }}</h1>
      </div>
    </div>
  </section>

  <section class="tenders">
    <div class="container">
      <h2 class="tenders__sr-heading">{{ t('procurement.heading') }}</h2>
      <ul v-if="items.length" class="tenders__grid">
        <li v-for="item in items" :key="item.slug" class="tender-card">
          <NuxtLink class="tender-card__link" :to="localePath(`/procurement/${item.slug}`)">
            <h3 class="tender-card__title">{{ item.title }}</h3>
            <span
              class="tender-card__status"
              :class="item.state === 'closed' && 'tender-card__status_done'"
            >{{ t(`procurement.state_${item.state}`) }}</span>
          </NuxtLink>
        </li>
      </ul>
      <p v-else class="tenders__empty">{{ t('procurement.empty') }}</p>

      <AppPagination :page="meta.current_page" :pages="meta.last_page" @change="page = $event" />
    </div>
  </section>
</template>
