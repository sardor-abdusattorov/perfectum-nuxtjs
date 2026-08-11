<script setup lang="ts">
const localePath = useLocalePath()
useSeo({ titleKey: 'seo.device' })
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

  <!-- DEVICE -->
  <section class="devices device-view">
      <div class="container">
          <div class="filter-search">
              <div class="filter-search__chips" role="tablist" aria-label="Категории устройств">
                  <button type="button" class="filter-search__chip" role="tab" aria-selected="false">Устройства CDMA <span class="filter-search__chip-count">22</span></button>
                  <button type="button" class="filter-search__chip filter-search__chip_active" role="tab" aria-selected="true">Устройства 5G SA <span class="filter-search__chip-count">3</span></button>
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

          <article class="device-view__item">
              <div class="device-view__aside">
                  <div class="device-view__media">
                      <img class="device-view__photo" src="/images/devices/tozed-zlt-x25-max2.jpg"
                          alt="Роутер Tozed ZLT X25 MAX2" width="433" height="562" />
                  </div>
                  <h2 class="device-view__name">Tozed ZLT X25 MAX2</h2>
                  <p class="device-view__lead">Краткая информация</p>
                  <p class="device-view__price"><b>1 850 000</b> сум</p>
                  <div class="device-view__actions">
                      <button type="button" class="device-view__buy">Рассрочка</button>
                      <NuxtLink class="device-view__link" :to="localePath('/offices')">Купить
                          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                              aria-hidden="true">
                              <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                                  stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                      </NuxtLink>
                  </div>
              </div>

              <div class="device-view__specs">
                  <h3 class="device-view__specs-title">Характеристики</h3>
                  <dl class="spec-list">
                      <div class="spec-list__row">
                          <dt class="spec-list__term">Тип устройства:</dt>
                          <dd class="spec-list__value">Домашний 5G роутер (Indoor CPE)</dd>
                      </div>
                      <div class="spec-list__row">
                          <dt class="spec-list__term">Сетевой стандарт:</dt>
                          <dd class="spec-list__value">5G NR SA/NSA, 4G LTE Cat 19, Wi-Fi 6 (AX3000)</dd>
                      </div>
                      <div class="spec-list__row">
                          <dt class="spec-list__term">Мощность передатчика:</dt>
                          <dd class="spec-list__value">До 23 dBm</dd>
                      </div>
                      <div class="spec-list__row">
                          <dt class="spec-list__term">Полосы частот:</dt>
                          <dd class="spec-list__value">до 200 МГц (5G SA), поддержка агрегации частот
                              (2CA NR, 5CC LTE)</dd>
                      </div>
                      <div class="spec-list__row">
                          <dt class="spec-list__term">Количество доступных пользователей:</dt>
                          <dd class="spec-list__value">до 128 устройств по Wi-Fi (64 на 2.4 ГГц + 64
                              на 5 ГГц)</dd>
                      </div>
                      <div class="spec-list__row">
                          <dt class="spec-list__term">Пропускная способность:</dt>
                          <dd class="spec-list__value">До 4.7 Гбит/с (загрузка), до 1.25 Гбит/с
                              (отдача)</dd>
                      </div>
                      <div class="spec-list__row">
                          <dt class="spec-list__term">Дополнительные функции:</dt>
                          <dd class="spec-list__value">CA (5CC LTE, 2CA NR), MIMO 4×4 DL, Mesh, WPS</dd>
                      </div>
                      <div class="spec-list__row">
                          <dt class="spec-list__term">Доступные порты:</dt>
                          <dd class="spec-list__value">2 × 2.5GE LAN/WAN, 1 × RJ-11 (опционально)</dd>
                      </div>
                      <div class="spec-list__row">
                          <dt class="spec-list__term">Безопасность:</dt>
                          <dd class="spec-list__value">WPA2/WPA3, NAT, VPN, TR-069</dd>
                      </div>
                      <div class="spec-list__row">
                          <dt class="spec-list__term">Тип антенн:</dt>
                          <dd class="spec-list__value">Встроенные</dd>
                      </div>
                      <div class="spec-list__row">
                          <dt class="spec-list__term">Крепление:</dt>
                          <dd class="spec-list__value">Настольное размещение</dd>
                      </div>
                      <div class="spec-list__row">
                          <dt class="spec-list__term">Размеры:</dt>
                          <dd class="spec-list__value">190 × 90 × 90 мм</dd>
                      </div>
                      <div class="spec-list__row">
                          <dt class="spec-list__term">Вес:</dt>
                          <dd class="spec-list__value">500 г</dd>
                      </div>
                      <div class="spec-list__row">
                          <dt class="spec-list__term">Материал корпуса:</dt>
                          <dd class="spec-list__value">Пластик</dd>
                      </div>
                  </dl>
              </div>
          </article>

          <div class="device-view__foot">
              <nav class="device-view__pager" aria-label="Другие устройства">
                  <a class="device-view__arrow device-view__arrow_prev" href="#"
                      aria-label="Предыдущее устройство">
                      <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                          aria-hidden="true">
                          <path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="1.8"
                              stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                  </a>
                  <span class="device-view__count"><b class="device-view__current">01</b>/03</span>
                  <a class="device-view__arrow device-view__arrow_next" href="#"
                      aria-label="Следующее устройство">
                      <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                          aria-hidden="true">
                          <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                              stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                  </a>
              </nav>
          </div>
      </div>
  </section>
</template>
