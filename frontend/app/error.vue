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
  ? t('error.not_found_text', 'Возможно, адрес набран с опечаткой или страница переехала.')
  : t('error.server_text', 'Мы уже знаем о неполадке и чиним её. Попробуйте обновить страницу через минуту.')))

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
        </div>
      </div>
    </section>
  </NuxtLayout>
</template>
