<script setup lang="ts">
const localePath = useLocalePath()
useSeo({ title: "Perfectum | Каталог устройств" })
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
                  <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">Устройства</span>
              </nav>
              <p class="page-hero__eyebrow page-hero__eyebrow_silver">Проверка совместимости</p>
              <h1 class="page-hero__title section__title">Каталог<br /><span
                      class="page-hero__title-red">устройств</span></h1>
              <p class="page-hero__subtitle">Роутеры для домашнего интернета 5G SA и список смартфонов, уже
                  получивших поддержку сети 5G Standalone.</p>
          </div>
      </div>
  </section>

  <!-- DEVICES -->
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
              <p><b>Устройства для сети 5G Standalone (5G SA).</b> 5G SA — наша текущая технология, первая
                  полностью самостоятельная сеть 5-го поколения в Центральной Азии. В отличие от CDMA, для
                  подключения подходит большинство современных смартфонов с поддержкой 5G — отдельная проверка
                  совместимости, как правило, не требуется.</p>
          </div>

          <ul class="brand-grid">
              <li class="brand-card">
                  <span class="brand-card__name brand-card__name_amgoo"><b>AM</b>GOO</span>
              </li>
              <li class="brand-card">
                  <img class="brand-card__logo" src="/images/brands/apple.svg" alt="Apple" loading="lazy" />
              </li>
              <li class="brand-card">
                  <img class="brand-card__logo" src="/images/brands/artel.svg" alt="artel" loading="lazy" />
              </li>
              <li class="brand-card">
                  <span class="brand-card__name brand-card__name_blue">AUDIOVOX</span>
              </li>
              <li class="brand-card">
                  <img class="brand-card__logo" src="/images/brands/blackberry.svg" alt="BlackBerry" loading="lazy" />
              </li>
              <li class="brand-card">
                  <span class="brand-card__name brand-card__name_red">BLESS</span>
              </li>
              <li class="brand-card">
                  <span class="brand-card__name brand-card__name_blue">Franklin Wireless</span>
              </li>
              <li class="brand-card">
                  <span class="brand-card__name brand-card__name_blue">Hisense</span>
              </li>
              <li class="brand-card">
                  <img class="brand-card__logo" src="/images/brands/htc.svg" alt="HTC" loading="lazy" />
              </li>
              <li class="brand-card">
                  <span class="brand-card__name brand-card__name_red">HUAWEI</span>
              </li>
              <li class="brand-card">
                  <span class="brand-card__name">HONOR</span>
              </li>
              <li class="brand-card">
                  <span class="brand-card__name brand-card__name_red">KYOCERA</span>
              </li>
          </ul>

          <p class="devices__note">Список протестированных и официально подтверждённых моделей будет
              дополняться по мере расширения сети 5G SA.</p>
      </div>
  </section>
</template>
