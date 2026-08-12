<script setup lang="ts">
import type { ApiResponse } from '~/types/api'

interface TariffFile {
  name: string
  url: string
}

const localePath = useLocalePath()
const t = useT()
const { locale } = useI18n()
const { $api } = useNuxtApp()

useSeo({ titleKey: 'seo.tariffs_archive' })

const { data: files } = await useAsyncData(
  'tariff-files',
  () => $api<ApiResponse<TariffFile[]>>('/tariffs/files').then(r => r.data),
  { watch: [locale], default: () => [] },
)
</script>

<template>
  <section class="page-hero page-hero_single_tariff">
    <div class="container">
      <div class="page-hero__inner">
        <nav class="page-hero__crumbs" :aria-label="t('common.breadcrumbs')">
          <NuxtLink class="page-hero__crumb" :to="localePath('/')">{{ t('common.home') }}</NuxtLink>
          <svg class="page-hero__crumb-sep" viewBox="0 0 24 24" fill="none">
            <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <NuxtLink class="page-hero__crumb" :to="localePath('/tariffs')">{{ t('seo.tariffs') }}</NuxtLink>
          <svg class="page-hero__crumb-sep" viewBox="0 0 24 24" fill="none">
            <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">{{ t('seo.tariffs_archive') }}</span>
        </nav>
        <h1 class="page-hero__title section__title">{{ t('seo.tariffs_archive') }}</h1>
      </div>
    </div>
  </section>

  <section class="tariffs-archive">
    <div class="container">
      <template v-if="files.length">
        <a
          v-for="file in files"
          :key="file.url"
          class="tariffs-archive__item"
          :href="file.url"
          target="_blank"
          rel="noopener"
          download
        >
          <span class="tariffs-archive__doc">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8l-5-5z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" />
              <path d="M14 3v5h5M9 13h6M9 17h6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </span>
          <span class="tariffs-archive__name">{{ file.name }}</span>
          <span class="tariffs-archive__download">
            {{ t('tariffs.download') }}
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 4v12m0 0l-5-5m5 5l5-5M5 20h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </span>
        </a>
      </template>
      <p v-else class="tariffs-archive__empty">{{ t('tariffs.archive_empty') }}</p>

      <div class="tariffs-archive__footer">
        <NuxtLink class="tariffs-archive__back" :to="localePath('/tariffs')">
          <svg viewBox="0 0 20 16" fill="none">
            <path d="M12 1l7 7-7 7M19 8H1" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          {{ t('tariffs.back_to_list') }}
        </NuxtLink>
      </div>
    </div>
  </section>
</template>
