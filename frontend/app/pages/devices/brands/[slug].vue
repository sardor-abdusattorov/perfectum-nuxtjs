<script setup lang="ts">
const localePath = useLocalePath()
useSeo({ titleKey: 'seo.devices' })

const models = [
  'AMGOO CX8R',
  'AMGOO AM88',
  'AMGOO AM89',
  'AMGOO AM409',
  'AMGOO AM415',
  'AMGOO AM528',
]

function deviceLink(model: string): string {
  return localePath(`/devices/${model.toLowerCase().replace(/\s+/g, '-')}`)
}
</script>

<template>
  <!-- PAGE HERO -->
  <section class="page-hero page-hero_inner page-hero_devices">
      <div class="container">
          <div class="page-hero__inner">
              <nav class="page-hero__crumbs" aria-label="Хлебные крошки">
                  <NuxtLink class="page-hero__crumb" :to="localePath('/')">Главная</NuxtLink>
                  <svg class="page-hero__crumb-sep" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                      fill="none" aria-hidden="true">
                      <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <NuxtLink class="page-hero__crumb" :to="localePath('/devices')">Устройства</NuxtLink>
                  <svg class="page-hero__crumb-sep" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                      fill="none" aria-hidden="true">
                      <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">AMGOO</span>
              </nav>
              <p class="page-hero__eyebrow page-hero__eyebrow_silver">Каталог устройств</p>
              <h1 class="page-hero__title section__title">Устройства<br /><span
                      class="page-hero__title-red">AMGOO</span></h1>
              <p class="page-hero__subtitle">Модели AMGOO, проверенные и официально подтверждённые для работы
                  в сети Perfectum.</p>
          </div>
      </div>
  </section>

  <!-- BRAND DEVICES -->
  <section class="devices">
      <div class="container">
          <div class="filter-search">
              <div class="filter-search__chips" role="tablist" aria-label="Категории устройств">
                  <button type="button" class="filter-search__chip filter-search__chip_active" role="tab" aria-selected="true">Устройства CDMA <span class="filter-search__chip-count">22</span></button>
                  <button type="button" class="filter-search__chip" role="tab" aria-selected="false">Устройства 5G SA <span class="filter-search__chip-count">3</span></button>
                  <button type="button" class="filter-search__chip" role="tab" aria-selected="false">Роутеры <span class="filter-search__chip-count">3</span></button>
                  <button type="button" class="filter-search__chip" role="tab" aria-selected="false">Сегодня в продаже <span class="filter-search__chip-count">3</span></button>
              </div>
          </div>

          <div class="callout">
              <p><b>Устройства для сети CDMA.</b> Телефоны и модемы, проверенные на совместимость с сетью
                  Perfectum CDMA. Устройства других производителей могут не поддерживать частоты сети —
                  перед покупкой сверьтесь со списком.</p>
          </div>

          <div class="brand-head">
              <NuxtLink class="brand-head__back" :to="localePath('/devices')" aria-label="Все бренды">
                  <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                      <path d="M20 12H6M12 6l-6 6 6 6" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
              </NuxtLink>
              <h2 class="brand-head__title">AMGOO</h2>
              <span class="brand-head__count">6 моделей</span>
          </div>

          <ul class="device-grid">
              <li v-for="model in models" :key="model" class="device-card">
                  <div class="device-card__media">
                      <svg class="device-card__silhouette" viewBox="0 0 64 64" fill="none"
                          xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                          <rect x="19" y="5" width="26" height="54" rx="6" stroke="currentColor"
                              stroke-width="2.4" />
                          <rect x="24" y="11" width="16" height="13" rx="2" stroke="currentColor"
                              stroke-width="2" />
                          <circle cx="26" cy="31" r="1.7" fill="currentColor" />
                          <circle cx="32" cy="31" r="1.7" fill="currentColor" />
                          <circle cx="38" cy="31" r="1.7" fill="currentColor" />
                          <circle cx="26" cy="36" r="1.7" fill="currentColor" />
                          <circle cx="32" cy="36" r="1.7" fill="currentColor" />
                          <circle cx="38" cy="36" r="1.7" fill="currentColor" />
                          <circle cx="26" cy="41" r="1.7" fill="currentColor" />
                          <circle cx="32" cy="41" r="1.7" fill="currentColor" />
                          <circle cx="38" cy="41" r="1.7" fill="currentColor" />
                          <circle cx="26" cy="46" r="1.7" fill="currentColor" />
                          <circle cx="32" cy="46" r="1.7" fill="currentColor" />
                          <circle cx="38" cy="46" r="1.7" fill="currentColor" />
                      </svg>
                  </div>
                  <p class="device-card__label">Модель</p>
                  <h3 class="device-card__name">{{ model }}</h3>
                  <NuxtLink class="device-card__link" :to="deviceLink(model)" :aria-label="`Модель ${model}`" />
              </li>
          </ul>

          <p class="devices__note">Список моделей пополняется по мере сертификации устройств в сети
              Perfectum.</p>
      </div>
  </section>
</template>
