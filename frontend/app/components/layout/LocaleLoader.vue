<script setup lang="ts">
const { active, hide } = useLocaleLoader()
const t = useT()
const router = useRouter()
const nuxtApp = useNuxtApp()

watch(active, value => {
  document.body.classList.toggle('overflow__hidden', value)
})

onScopeDispose(() => {
  if (import.meta.client) {
    document.body.classList.remove('overflow__hidden')
  }
})

nuxtApp.hook('page:finish', hide)
nuxtApp.hook('vue:error', hide)

router.afterEach((_to, _from, failure) => {
  if (failure) {
    hide()
  }
})
</script>

<template>
  <div class="locale-loader" :class="active && 'locale-loader_active'" role="status">
    <span class="locale-loader__spinner" aria-hidden="true"></span>
    <span class="visually-hidden">{{ t('common.loading') }}</span>
  </div>
</template>
