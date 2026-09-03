<script setup lang="ts">
import type { NuxtError } from '#app'

const props = defineProps<{ error: NuxtError }>()

await useSite()

const localePath = useLocalePath()
const t = useT()

const code = computed(() => props.error?.statusCode ?? 500)
const missing = computed(() => code.value === 404)

const title = computed(() => (missing.value
  ? t('error.not_found_title', 'Такой страницы нет')
  : t('error.server_title', 'Что-то пошло не так')))

const text = computed(() => (missing.value
  ? t('error.not_found_text', 'Возможно, адрес набран с опечаткой или страница переехала. Загляните в разделы ниже — то, что вы искали, скорее всего там.')
  : t('error.server_text', 'Мы уже знаем о неполадке и чиним её. Попробуйте обновить страницу через минуту.')))

const links = computed(() => [
  { to: '/tariffs', label: t('error.link_tariffs', 'Тарифы') },
  { to: '/services', label: t('error.link_services', 'Услуги') },
  { to: '/devices', label: t('error.link_devices', 'Устройства') },
  { to: '/coverage-area', label: t('error.link_coverage', 'Карта покрытия') },
  { to: '/news', label: t('error.link_news', 'Новости') },
  { to: '/help/contact', label: t('error.link_contact', 'Связаться с нами') },
])

function leave(path: string): void {
  clearError({ redirect: localePath(path) })
}

useHead({
  title: `${code.value} — Perfectum`,
  meta: [{ name: 'robots', content: 'noindex, follow' }],
})
</script>

<template>
  <NuxtLayout name="default">
    <section class="error-page">
      <div class="container">
        <div class="error-page__inner">
          <p class="error-page__code">{{ code }}</p>
          <h1 class="error-page__title">{{ title }}</h1>
          <p class="error-page__text">{{ text }}</p>

          <button type="button" class="error-page__btn" @click="leave('/')">
            {{ t('error.home', 'На главную') }}
          </button>

          <nav class="error-page__links" :aria-label="t('error.links_label', 'Разделы сайта')">
            <button
              v-for="link in links"
              :key="link.to"
              type="button"
              class="error-page__link"
              @click="leave(link.to)"
            >{{ link.label }}</button>
          </nav>
        </div>
      </div>
    </section>
  </NuxtLayout>
</template>
