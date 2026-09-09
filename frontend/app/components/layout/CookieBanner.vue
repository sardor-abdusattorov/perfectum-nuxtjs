<script setup lang="ts">
const { visible, accept } = useConsent()
const settings = useSiteSettings()
const t = useT()

const text = computed(() => rich(settings.value?.cookie?.text))
const accepted = computed(() => settings.value?.cookie?.accept || t('cookie.accept', 'Принять'))
</script>

<template>
  <ClientOnly>
    <div v-if="visible && text" class="cookies">
      <div class="cookies__text" v-html="text" />
      <button type="button" class="cookies__accept" @click="accept">
        {{ accepted }}
      </button>
    </div>
  </ClientOnly>
</template>
