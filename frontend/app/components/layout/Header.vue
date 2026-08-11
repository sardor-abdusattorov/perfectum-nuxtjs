<script setup lang="ts">
const localePath = useLocalePath()
const menu = useMenu('header')
const setting = useSetting()
const t = useT()
const mobileMenu = useMobileMenu()
</script>

<template>
  <header class="header">
      <div class="container">
          <div class="header__row">
              <div class="header__logo">
                  <NuxtLink :to="localePath('/')" class="header__logo-link">
                      <img src="/images/logo.svg" alt="Perfectum 5G" class="header__logo-img" />
                  </NuxtLink>
                  <span class="header__logo-divider" aria-hidden="true"></span>
                  <NuxtLink :to="localePath('/cdma')" class="header__logo-brand">CDMA</NuxtLink>
              </div>
              <nav class="header__nav">
                  <ul class="header__menu">
                      <li
                          v-for="item in menu"
                          :key="item.id"
                          class="header__menu-item"
                          :class="item.children.length && 'header__menu-item_has-submenu'"
                      >
                          <LayoutMenuLink :item="item" link-class="header__link" />

                          <div v-if="item.children.length" class="header__submenu">
                              <ul class="header__submenu-list">
                                  <li v-for="child in item.children" :key="child.id" class="header__submenu-item">
                                      <LayoutMenuLink :item="child" link-class="header__submenu-link" />
                                  </li>
                              </ul>
                          </div>
                      </li>
                  </ul>
              </nav>
              <div class="header__info">
                  <LayoutLangSwitcher />

                  <a :href="setting('account_url', '#')" class="header__account" target="_blank" rel="noopener">
                      <span class="header__account-icon">
                          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                      </span>
                      {{ t('header.account', 'Личный кабинет') }}
                  </a>
                  <div class="header__hamburger">
                      <button
                          type="button"
                          class="header__hamburger-button"
                          :aria-label="t('header.open_menu', 'Открыть меню')"
                          :aria-expanded="mobileMenu.open.value"
                          @click="mobileMenu.toggle()"
                      >
                          <span class="header__hamburger-line"></span>
                          <span class="header__hamburger-line"></span>
                          <span class="header__hamburger-line"></span>
                      </button>
                  </div>
              </div>
          </div>
      </div>
  </header>
</template>
