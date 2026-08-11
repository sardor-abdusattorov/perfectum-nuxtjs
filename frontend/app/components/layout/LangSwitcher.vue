<script setup lang="ts">
const { locale, locales } = useI18n()
const switchLocalePath = useSwitchLocalePath()

const codes = computed(() => locales.value.map(item => (typeof item === 'string' ? item : item.code)))
</script>

<template>
  <div class="header__lang">
    <button type="button" class="header__lang-toggle">
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
      >
        {{ code.toUpperCase() }}
      </NuxtLink>
    </div>
  </div>
</template>
