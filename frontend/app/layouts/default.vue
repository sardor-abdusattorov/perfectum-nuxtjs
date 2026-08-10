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
