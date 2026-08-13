<script setup lang="ts">
const MIN_VISIBLE = 600
const FAILSAFE = 6000

const { active } = useLocaleLoader()
const t = useT()
const router = useRouter()
const nuxtApp = useNuxtApp()

let shownAt = 0
let failsafe: ReturnType<typeof setTimeout> | undefined

watch(active, value => {
  clearTimeout(failsafe)

  if (value) {
    shownAt = Date.now()
    failsafe = setTimeout(() => (active.value = false), FAILSAFE)
  }
})

/**
 * The overlay outlives page:finish by the rest of MIN_VISIBLE, so the
 * scroll reset and content swap happen behind it instead of in front
 * of the visitor.
 */
function release(): void {
  if (!active.value) {
    return
  }

  const wait = Math.max(0, MIN_VISIBLE - (Date.now() - shownAt))

  setTimeout(() => (active.value = false), wait)
}

nuxtApp.hook('page:finish', release)
nuxtApp.hook('vue:error', () => (active.value = false))

router.afterEach((_to, _from, failure) => {
  if (failure) {
    active.value = false
  }
})
</script>

<template>
  <div class="locale-loader" :class="active && 'locale-loader_active'" role="status">
    <img src="/images/logo.svg" alt="Perfectum 5G" class="locale-loader__logo" />
    <span class="locale-loader__spinner" aria-hidden="true"></span>
    <span class="visually-hidden">{{ t('common.loading') }}</span>
  </div>
</template>
