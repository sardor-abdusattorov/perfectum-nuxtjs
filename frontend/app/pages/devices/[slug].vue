<script setup lang="ts">
const localePath = useLocalePath()
const route = useRoute()
const t = useT()

const slug = computed(() => String(route.params.slug ?? ''))

const { data: device } = await useDevice(slug)

if (!device.value) {
  throw createError({ statusCode: 404, statusMessage: 'Not Found', fatal: true })
}

const { data: catalog } = await useDeviceCatalog()
const tabs = useDeviceTabs(catalog)

useSeo({ page: 'devices', title: () => device.value?.name ?? '' })

const network = computed(() => device.value?.category?.network ?? '5g')
const activeTab = computed(() => String(device.value?.category?.id ?? ''))

const price = computed(() => (
  device.value?.price == null ? '' : device.value.price.toLocaleString('ru-RU')
))

/**
 * The pager walks the device's own category in catalogue order and wraps at
 * both ends, so the arrows always lead somewhere.
 */
const siblings = computed(() => {
  const devices = catalog.value?.devices ?? []
  const category = device.value?.category?.id

  return category == null ? devices : devices.filter(item => item.category?.id === category)
})

const position = computed(() => siblings.value.findIndex(item => item.slug === slug.value))

const pager = computed(() => {
  const list = siblings.value
  const index = position.value

  if (list.length < 2 || index === -1) {
    return null
  }

  return {
    prev: list[(index - 1 + list.length) % list.length]!,
    next: list[(index + 1) % list.length]!,
    current: String(index + 1).padStart(2, '0'),
    total: String(list.length).padStart(2, '0'),
  }
})
</script>

<template>
  <template v-if="device">
      <!-- PAGE HERO -->
      <section class="page-hero page-hero_inner page-hero_devices">
          <div class="container">
              <div class="page-hero__inner">
                  <nav class="page-hero__crumbs" :aria-label="t('common.breadcrumbs')">
                      <NuxtLink class="page-hero__crumb" :to="localePath('/')">{{ t('common.home') }}</NuxtLink>
                      <svg class="page-hero__crumb-sep" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                          fill="none" aria-hidden="true">
                          <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6"
                              stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                      <NuxtLink class="page-hero__crumb" :to="localePath('/devices')">{{ t('seo.devices') }}</NuxtLink>
                      <svg class="page-hero__crumb-sep" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                          fill="none" aria-hidden="true">
                          <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6"
                              stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                      <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">{{ device.name }}</span>
                  </nav>
                  <p class="page-hero__eyebrow page-hero__eyebrow_silver">{{ t('devices.hero_eyebrow') }}</p>
                  <h1 class="page-hero__title section__title" v-html="t('devices.hero_title')"></h1>
                  <p class="page-hero__subtitle">{{ t('devices.hero_subtitle') }}</p>
              </div>
          </div>
      </section>

      <!-- DEVICE -->
      <section class="devices device-view">
          <div class="container">
              <DeviceChips :tabs="tabs" :active="activeTab" linked />

              <div class="callout">
                  <p v-html="t(network === 'cdma' ? 'devices.callout_cdma' : 'devices.callout_5g')"></p>
              </div>

              <article class="device-view__item">
                  <div class="device-view__aside">
                      <div class="device-view__media" :class="!device.image && 'device-view__media_empty'">
                          <img v-if="device.image" class="device-view__photo" :src="device.image"
                              :alt="device.name" width="433" height="562" />
                          <DeviceSilhouette v-else />
                      </div>
                      <h2 class="device-view__name">{{ device.name }}</h2>
                      <p v-if="device.excerpt" class="device-view__lead">{{ device.excerpt }}</p>
                      <p v-if="price" class="device-view__price"><b>{{ price }}</b> {{ t('devices.currency') }}</p>
                      <div v-if="device.in_stock" class="device-view__actions">
                          <NuxtLink class="device-view__buy" :to="localePath('/help/contact')">{{ t('devices.installment') }}</NuxtLink>
                          <NuxtLink class="device-view__link" :to="localePath('/offices')">{{ t('devices.buy') }}
                              <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                  aria-hidden="true">
                                  <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                                      stroke-linecap="round" stroke-linejoin="round" />
                              </svg>
                          </NuxtLink>
                      </div>
                  </div>

                  <div v-if="device.specs.length || device.content" class="device-view__specs">
                      <template v-if="device.specs.length">
                          <h3 class="device-view__specs-title">{{ t('devices.specs_title') }}</h3>
                          <dl class="spec-list">
                              <div v-for="(spec, index) in device.specs" :key="index" class="spec-list__row">
                                  <dt class="spec-list__term">{{ spec.label }}</dt>
                                  <dd class="spec-list__value">{{ spec.value }}</dd>
                              </div>
                          </dl>
                      </template>

                      <div v-if="device.content" class="device-view__desc" v-html="device.content"></div>
                  </div>
              </article>

              <div v-if="pager" class="device-view__foot">
                  <nav class="device-view__pager" :aria-label="t('devices.pager_label')">
                      <NuxtLink class="device-view__arrow device-view__arrow_prev"
                          :to="localePath(`/devices/${pager.prev.slug}`)" :aria-label="t('common.prev')">
                          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                              aria-hidden="true">
                              <path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="1.8"
                                  stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                      </NuxtLink>
                      <span class="device-view__count"><b class="device-view__current">{{ pager.current }}</b>/{{ pager.total }}</span>
                      <NuxtLink class="device-view__arrow device-view__arrow_next"
                          :to="localePath(`/devices/${pager.next.slug}`)" :aria-label="t('common.next')">
                          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                              aria-hidden="true">
                              <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                                  stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                      </NuxtLink>
                  </nav>
              </div>
          </div>
      </section>
  </template>
</template>
