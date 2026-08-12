<script setup lang="ts">
const localePath = useLocalePath()
const menu = useMenu('header')
const setting = useSetting()
const t = useT()
const mobileMenu = useMobileMenu()
const route = useRoute()

const expanded = ref<number | null>(null)

watch(() => route.fullPath, () => {
  expanded.value = null
})
</script>

<template>
  <div class="menu" :class="mobileMenu.open.value && 'menu_open'" @click.self="mobileMenu.close()">
      <div class="menu__panel">
          <div class="menu__head">
              <NuxtLink :to="localePath('/')" class="menu__logo">
                  <img src="/images/logo-white.svg" alt="Perfectum 5G" />
              </NuxtLink>
              <button type="button" class="menu__close" :aria-label="t('header.close_menu')" @click="mobileMenu.close()">
                  <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                  </svg>
              </button>
          </div>
          <ul class="menu__list">
              <li
                  v-for="item in menu"
                  :key="item.id"
                  class="menu__item"
                  :class="[item.children.length && 'menu__item_has-submenu', expanded === item.id && 'menu__item_open']"
              >
                  <button
                      v-if="item.children.length"
                      type="button"
                      class="menu__link menu__link_toggle"
                      @click="expanded = expanded === item.id ? null : item.id"
                  >
                      {{ item.name }}
                  </button>
                  <LayoutMenuLink v-else :item="item" link-class="menu__link" />

                  <ul v-if="item.children.length" class="menu__submenu">
                      <li v-for="child in item.children" :key="child.id" class="menu__submenu-item">
                          <LayoutMenuLink :item="child" link-class="menu__submenu-link" />
                      </li>
                  </ul>
              </li>
          </ul>
          <a :href="setting('account_url', '#')" class="menu__account" target="_blank" rel="noopener">
              {{ t('header.account') }}
          </a>
      </div>
  </div>
</template>
