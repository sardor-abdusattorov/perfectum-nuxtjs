<script setup lang="ts">
const localePath = useLocalePath()
const t = useT()
</script>

<template>
  <section class="cdma-footer">
      <div class="container">
          <nav class="cdma-footer__nav" :aria-label="t('cdma.sections_label')">
              <NuxtLink class="cdma-footer__back" :aria-label="t('cdma.to_home')" :to="localePath('/')">
                  <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M19 12H5M11 18l-6-6 6-6" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
              </NuxtLink>
              <NuxtLink class="cdma-footer__link" :to="localePath('/cdma')">{{ t('seo.cdma') }}</NuxtLink>
              <NuxtLink class="cdma-footer__link" :to="localePath('/cdma/dealers')">{{ t('cdma.tab_dealers') }}</NuxtLink>
              <NuxtLink class="cdma-footer__link" :to="localePath('/about-company')">{{ t('seo.about') }}</NuxtLink>
          </nav>
          <span class="cdma-footer__copy">{{ t('cdma.copyright') }}</span>
      </div>
  </section>
</template>
