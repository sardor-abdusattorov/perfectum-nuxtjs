<script setup lang="ts">
const { locale, locales } = useI18n()
const switchLocalePath = useSwitchLocalePath()
const route = useRoute()

const open = ref(false)
const root = ref<HTMLElement | null>(null)

const codes = computed(() => locales.value.map(item => (typeof item === 'string' ? item : item.code)))

watch(() => route.fullPath, () => {
  open.value = false
})

function onDocumentClick(event: MouseEvent) {
  if (root.value && !root.value.contains(event.target as Node)) {
    open.value = false
  }
}

onMounted(() => document.addEventListener('click', onDocumentClick))
onBeforeUnmount(() => document.removeEventListener('click', onDocumentClick))
</script>

<template>
  <div ref="root" class="header__lang" :class="open && 'header__lang_open'">
    <button type="button" class="header__lang-toggle" :aria-expanded="open" @click="open = !open">
      <span class="header__lang-current">{{ locale.toUpperCase() }}</span>
      <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
    </button>

    <div class="header__lang-menu">
      <NuxtLink
        v-for="code in codes"
        :key="code"
        class="header__lang-option"
        :class="code === locale && 'header__lang-option_active'"
        :to="switchLocalePath(code)"
        :hreflang="code"
        rel="alternate"
        @click="open = false"
      >
        {{ code.toUpperCase() }}
      </NuxtLink>
    </div>
  </div>
</template>
