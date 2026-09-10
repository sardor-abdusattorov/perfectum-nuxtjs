<script setup lang="ts">
const DEFAULT_TEXT: Record<string, string> = {
  ru: 'Мы используем файлы cookie, чтобы сайт работал лучше. Продолжая пользоваться сайтом, вы соглашаетесь с их использованием.',
  uz: 'Sayt yaxshiroq ishlashi uchun cookie fayllaridan foydalanamiz. Saytdan foydalanishda davom etib, ularning ishlatilishiga rozilik bildirasiz.',
}

const { visible, accept } = useConsent()
const settings = useSiteSettings()
const { locale } = useI18n()
const t = useT()

const text = computed(() => rich(settings.value?.cookie?.text) || DEFAULT_TEXT[locale.value] || DEFAULT_TEXT.ru)
const accepted = computed(() => settings.value?.cookie?.accept || t('cookie.accept', 'Принять'))
</script>

<template>
  <div v-if="visible" class="cookies">
    <div class="cookies__text" v-html="text" />
    <button type="button" class="cookies__accept" @click="accept">
      {{ accepted }}
    </button>
  </div>
</template>
