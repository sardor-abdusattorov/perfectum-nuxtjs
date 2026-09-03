<script setup lang="ts">
const localePath = useLocalePath()
const menu = useMenu('header')
const setting = useSetting()
const t = useT()
const mobileMenu = useMobileMenu()

/**
 * Following a link out of a submenu leaves the cursor sitting on the item that
 * opened it, so the dropdown would hang over the page just arrived at. Only
 * that one item is held shut, and only until the cursor leaves it — a
 * neighbour has nothing to do with the link that was clicked and must open on
 * hover straight away.
 */
const closedItem = ref<number | null>(null)

function releaseSubmenu(id: number): void {
  if (closedItem.value === id) {
    closedItem.value = null
  }
}

/**
 * Hung on the click and not on the route: following FAQ while already on the
 * FAQ page changes no address, so nothing would ever tell the menu to close —
 * it stayed open, and hovering a neighbour then put two dropdowns on screen
 * side by side.
 */
function closeSubmenu(id: number, event: MouseEvent): void {
  closedItem.value = id

  if (!import.meta.client) {
    return
  }

  // A mouse click has to give the focus up, or `:focus-within` reopens the
  // dropdown the moment the cursor leaves. `detail` is 0 when the link was
  // followed from the keyboard, where the focus is the only way to tell where
  // you are, so there it stays put.
  if (event.detail > 0 && document.activeElement instanceof HTMLElement) {
    document.activeElement.blur()
  }
}
</script>

<template>
  <header class="header">
      <div class="container">
          <div class="header__row">
              <div class="header__logo">
                  <NuxtLink :to="localePath('/')" class="header__logo-link">
                      <img src="/images/logo.svg" alt="Perfectum 5G" class="header__logo-img" width="203" height="31" />
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
                          :data-menu-item="item.id"
                          :class="[
                              item.children.length && 'header__menu-item_has-submenu',
                              closedItem === item.id && 'header__menu-item_closed',
                          ]"
                          @pointerleave="releaseSubmenu(item.id)"
                          @click="closeSubmenu(item.id, $event)"
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
                          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                              <path d="M19.7274 20.4471C19.2716 19.1713 18.2672 18.0439 16.8701 17.2399C15.4729 16.4358 13.7611 16 12 16C10.2389 16 8.52706 16.4358 7.12991 17.2399C5.73276 18.0439 4.72839 19.1713 4.27259 20.4471" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                              <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                          </svg>
                      </span>
                      {{ t('header.account') }}
                  </a>
                  <div class="header__hamburger">
                      <button
                          type="button"
                          class="header__hamburger-button"
                          :aria-label="t('header.open_menu')"
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
