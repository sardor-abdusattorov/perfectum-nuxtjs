<script setup lang="ts">
const localePath = useLocalePath()
const t = useT()

useSeo({ page: 'actions', titleKey: 'seo.actions' })

const { search, category, page } = useListQuery()

const { data } = await useActionsList({ network: '5g', category, search, page }, true)

const items = computed(() => data.value?.items ?? [])
const meta = computed(() => data.value?.meta ?? { current_page: 1, last_page: 1, total: 0 })
const categories = computed(() => data.value?.categories ?? [])

</script>

<template>
  <section class="page-hero page-hero_inner page-hero_actions">
    <div class="container">
      <div class="page-hero__inner">
        <nav class="page-hero__crumbs" :aria-label="t('common.breadcrumbs')">
          <NuxtLink class="page-hero__crumb" :to="localePath('/')">{{ t('common.home') }}</NuxtLink>
          <svg class="page-hero__crumb-sep" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">{{ t('seo.actions') }}</span>
        </nav>
        <h1 class="page-hero__title section__title">{{ t('seo.actions') }}</h1>
      </div>
    </div>
  </section>

  <section class="actions">
    <div class="container">
      <div class="filter-search">
        <SearchField
          v-model="search"
          :placeholder="t('actions.search_placeholder')"
          :label="t('actions.search_label')"
        />
        <div class="filter-search__chips" role="tablist" :aria-label="t('actions.categories_label')">
          <button
            type="button"
            class="filter-search__chip"
            :class="!category && 'filter-search__chip_active'"
            role="tab"
            :aria-selected="!category"
            @click="category = ''"
          >{{ t('actions.all') }}</button>
          <button
            v-for="item in categories"
            :key="item.id"
            type="button"
            class="filter-search__chip"
            :class="item.slug === category && 'filter-search__chip_active'"
            role="tab"
            :aria-selected="item.slug === category"
            @click="category = item.slug ?? ''"
          >{{ item.name }}</button>
        </div>
      </div>

      <div class="section-head">
        <h2 class="section-head__title">{{ t('actions.all_title') }}</h2>
        <span class="section-head__count">{{ meta.total }}</span>
      </div>

      <ul v-if="items.length" class="promo-grid">
        <li v-for="item in items" :key="item.slug" class="promo-card">
          <NuxtLink class="promo-card__link" :to="localePath(`/actions/${item.slug}`)">
            <div
              class="promo-card__media"
              :class="item.preview_image && 'promo-card__media_photo'"
              :style="cover(item.preview_image)"
            >
              <span v-if="item.badge" class="promo-card__badge">{{ item.badge }}</span>
              <span v-else></span>
              <span v-if="item.category" class="promo-card__cat">{{ item.category.name }}</span>
            </div>
            <div class="promo-card__body">
              <h3 class="promo-card__title">{{ item.title }}</h3>
              <p class="promo-card__text">{{ item.excerpt }}</p>
              <div class="promo-card__foot">
                <span v-if="item.ends_at" class="promo-card__date">{{ t('actions.until') }} {{ dateShort(item.ends_at) }}</span>
                <span v-else></span>
                <span class="promo-card__arrow" aria-hidden="true">
                  <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
                </span>
              </div>
            </div>
          </NuxtLink>
        </li>
      </ul>
      <p v-else class="actions__empty">{{ t('actions.empty') }}</p>

      <AppPagination :page="meta.current_page" :pages="meta.last_page" @change="page = $event" />
    </div>
  </section>
</template>
