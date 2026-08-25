<script setup lang="ts">
const head = useLocaleHead({ lang: true, dir: false, seo: true })
const mobileMenu = useMobileMenu()

useHead(() => ({
  htmlAttrs: { lang: head.value.htmlAttrs?.lang },
  link: head.value.link,
  meta: head.value.meta,
}))
</script>

<template>
  <LayoutPreviewBanner />

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

  <div class="overlay" :class="mobileMenu.open.value && 'overlay_open'" @click="mobileMenu.close()" />
</template>
