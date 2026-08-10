<script setup lang="ts">
// canonical + hreflang alternate for every locale, the equivalent of the
// LaravelLocalization block at the bottom of seo.blade.php
const head = useLocaleHead({ lang: true, dir: false, seo: true })

useHead({
  htmlAttrs: { lang: head.value.htmlAttrs?.lang },
  link: head.value.link,
  meta: head.value.meta,
})
</script>

<template>
  <div class="wrapper">
    <AppHeader />

    <main>
      <slot />
    </main>

    <AppFooter />
  </div>

  <AppMobileMenu />
  <TariffConnectModal />

  <div class="overlay" />
</template>
