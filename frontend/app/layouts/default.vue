<script setup lang="ts">
const head = useLocaleHead({ lang: true, dir: false, seo: true })

useHead({
  htmlAttrs: { lang: head.value.htmlAttrs?.lang },
  link: head.value.link,
  meta: head.value.meta,
})
</script>

<template>
  <div class="wrapper">
    <LayoutHeader />

    <main>
      <slot />
    </main>

    <LayoutFooter />
  </div>

  <LayoutMobileMenu />
  <LayoutTariffModal />
  <LayoutCookieBanner />

  <div class="overlay" />
</template>
