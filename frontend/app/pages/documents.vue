<script setup lang="ts">
import type { ApiResponse } from '~/types/api'

interface DocumentDownload {
  language: string
  url: string | null
  size: string | null
}

interface DocumentItem {
  name: string
  url: string | null
  size: string | null
  files: DocumentDownload[]
}

interface DocumentGroup {
  id: number | null
  name: string | null
  documents: DocumentItem[]
}

const { locale } = useI18n()
const { $api } = useNuxtApp()
const t = useT()

useSeo({ page: 'documents', titleKey: 'seo.documents' })

await useBlocks('documents')
const hero = useBlock('documents', 'page_hero')

const { data } = await useAsyncData(
  'documents',
  () => $api<ApiResponse<DocumentGroup[]>>('/documents').then(response => response.data),
  { watch: [locale], default: () => [] as DocumentGroup[] },
)

const groups = computed(() => data.value ?? [])

/**
 * The card downloads the visitor's own language; the rest are offered next
 * to it so a document translated three ways stays one row.
 */
function translations(doc: DocumentItem): DocumentDownload[] {
  return doc.files.filter(file => file.url !== null && file.url !== doc.url)
}
</script>

<template>
  <PageHero
    variant="page-hero_inner page-hero_company"
    :crumb="t('seo.documents')"
    :eyebrow="hero.eyebrow"
    eyebrow-silver
    :title="rich(hero.title, { accent: 'page-hero__title-red' })"
    :subtitle="rich(hero.subtitle)"
  />

  <section class="company">
    <div class="container">
      <CompanyNav active="documents" />

      <div v-for="group in groups" :key="group.id ?? 'other'" class="doc-group">
        <h2 v-if="group.name" class="doc-group__title">{{ group.name }}</h2>
        <ul class="doc-list">
          <li v-for="doc in group.documents" :key="doc.url ?? doc.name" class="doc-item">
            <span class="doc-item__icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="67" height="67" viewBox="0 0 67 67"
              fill="none">
              <rect x="0.5" y="0.5" width="66" height="66" rx="13.5" fill="white"
              stroke="#E60000" />
              <path
              d="M12 43.6516V58.7524C12 59.9933 12.9966 61 14.2249 61H53.7751C55.0034 61 56 59.9933 56 58.7524V43.6516H12ZM22.7074 41.0997C22.0005 41.0997 21.3168 40.8305 20.6911 40.3037C19.6366 39.414 19.5554 38.4307 19.6597 37.7635C20.1696 34.755 25.7087 32.0041 28.9302 30.6462C30.68 26.2213 32.1981 21.0121 33.1367 17.1023C31.2594 13.0403 30.5989 10.1723 31.1783 8.5452C31.468 7.73748 32.059 7.21071 32.8933 7.02341L33.0092 7L33.1251 7.01171C33.2178 7.02341 34.0406 7.15218 34.7127 8.06525C35.5818 9.25927 35.7788 11.1908 35.2921 13.8129C35.153 14.5621 34.9213 15.6391 34.62 16.9267C36.335 20.5322 38.7453 24.5122 40.7153 27.2749C42.3608 27.0408 43.9252 26.9588 45.2462 27.0993C47.7145 27.3568 48.236 28.4455 48.3287 29.0776C48.4677 30.0024 47.7956 30.9623 46.6484 31.4539C45.0608 32.1329 42.4651 31.9573 40.3561 29.1947C40.2634 29.0776 40.1707 28.9488 40.078 28.8201C37.3663 29.2766 34.2144 30.1429 31.0161 31.3486C30.68 31.4773 30.3555 31.6061 30.0311 31.7349C27.8641 37.0963 25.7435 40.1749 23.7156 40.9124C23.3911 41.0412 23.0435 41.0997 22.7074 41.0997ZM28.1538 32.5426C23.5533 34.638 21.2705 36.7099 21.0619 38.0093C21.0155 38.2903 21.0155 38.7234 21.6065 39.215C22.1512 39.6716 22.6726 39.7886 23.252 39.5779C24.8628 38.9926 26.5778 36.1832 28.1538 32.5426ZM41.7119 28.5977C43.2763 30.4589 45.0377 30.6111 46.1038 30.1429C46.66 29.9087 46.9729 29.5107 46.9381 29.2883C46.9033 29.0542 46.3819 28.6562 45.1072 28.5274C44.157 28.4338 43.0097 28.4572 41.7119 28.5977ZM34.1101 19.0689C33.4032 21.8199 32.4414 25.1327 31.3174 28.3401C31.1204 28.8903 30.9349 29.4171 30.7379 29.9321C33.2526 28.9957 36.2886 28.0826 39.1741 27.5324C37.6213 25.2966 35.7092 22.1945 34.1101 19.0689ZM33.0672 8.47496C32.8006 8.56861 32.6268 8.7442 32.5225 9.04856C32.1169 10.1723 32.65 12.2911 33.6813 14.7962C33.774 14.3514 33.8551 13.9417 33.9247 13.5671C34.4577 10.6523 33.9479 9.39974 33.5886 8.91979C33.3916 8.65055 33.183 8.52179 33.0672 8.47496Z"
              fill="#E60000" />
              <path
              d="M44.9949 50.9491H42.0814V47.9847H44.9949C45.542 47.9847 45.9873 47.5394 45.9873 46.9924C45.9873 46.4453 45.542 46 44.9949 46H41.1018C40.5547 46 40.1094 46.4453 40.1094 46.9924V56.8651C40.1094 57.4122 40.5547 57.8575 41.1018 57.8575C41.6489 57.8575 42.0941 57.4122 42.0941 56.8651V52.9211H45.0076C45.5547 52.9211 46 52.4758 46 51.9288C45.9873 51.3817 45.542 50.9491 44.9949 50.9491ZM24.0025 46.0127H21.9924C21.4453 46.0127 21 46.458 21 47.0051V56.8779C21 57.4249 21.4453 57.8702 21.9924 57.8702C22.5394 57.8702 22.9847 57.4249 22.9847 56.8779V52.9338H24.0025C25.9109 52.9338 27.4631 51.3817 27.4631 49.4733C27.4631 47.5649 25.9109 46.0127 24.0025 46.0127ZM24.0025 50.9491H22.9847V47.9847H24.0025C24.8168 47.9847 25.4784 48.6463 25.4784 49.4606C25.4784 50.2748 24.8168 50.9491 24.0025 50.9491ZM32.5267 46.0127H29.9313C29.3842 46.0127 28.9389 46.458 28.9389 47.0051V56.8779C28.9389 57.4249 29.3842 57.8702 29.9313 57.8702H32.5267C35.7964 57.8702 38.4555 55.2112 38.4555 51.9415C38.4555 48.659 35.7964 46.0127 32.5267 46.0127ZM32.5267 55.8728H30.9237V47.972H32.5267C34.7023 47.972 36.4707 49.7405 36.4707 51.916C36.4835 54.1043 34.7023 55.8728 32.5267 55.8728Z"
              fill="#EFF3F5" />
              </svg>
            </span>
            <div class="doc-item__body">
              <h3 class="doc-item__name">{{ doc.name }}</h3>
              <p v-if="doc.size" class="doc-item__size">{{ doc.size }}</p>
              <p v-if="translations(doc).length" class="doc-item__langs">
                <a
                  v-for="file in translations(doc)"
                  :key="file.url!"
                  class="doc-item__lang"
                  :href="file.url!"
                  :hreflang="file.language"
                  download
                >{{ file.language.toUpperCase() }}</a>
              </p>
            </div>
            <a v-if="doc.url" class="doc-item__download" :href="doc.url" download>
              {{ t('documents.download') }}
              <svg viewBox="0 0 24 24"
              fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <path d="M12 4v11m0 0l-4-4m4 4l4-4M5 20h14" stroke="currentColor"
              stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </a>
          </li>
        </ul>
      </div>

      <p v-if="!groups.length" class="doc-empty">{{ t('documents.empty') }}</p>
    </div>
  </section>
</template>
