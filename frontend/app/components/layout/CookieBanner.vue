<script setup lang="ts">
/**
 * Only a stand-in for an empty panel field — the real wording belongs in the
 * admin, where it can name the services and link the policy. It says what the
 * site actually does rather than «for the site to work better», because the
 * button beside it no longer decides anything: the counters load either way.
 */
const DEFAULT_TEXT: Record<string, string> = {
  ru: 'Мы используем файлы cookie и сервисы аналитики, чтобы понимать, как вы пользуетесь сайтом, и делать его удобнее.',
  uz: 'Saytdan qanday foydalanayotganingizni tushunish va uni qulayroq qilish uchun cookie fayllari va tahlil xizmatlaridan foydalanamiz.',
}

const { visible, accept } = useConsent()
const settings = useSiteSettings()
const { locale } = useI18n()
const t = useT()

const text = computed(() => rich(settings.value?.cookie?.text) || DEFAULT_TEXT[locale.value] || DEFAULT_TEXT.ru)
const accepted = computed(() => settings.value?.cookie?.accept || t('cookie.accept', 'Понятно'))
</script>

<template>
  <div v-if="visible" class="cookies">
    <div class="cookies__text" v-html="text" />
    <button type="button" class="cookies__accept" @click="accept">
      {{ accepted }}
    </button>
  </div>
</template>
