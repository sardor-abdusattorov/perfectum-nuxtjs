<script setup lang="ts">
const localePath = useLocalePath()
const route = useRoute()
const t = useT()

const slug = computed(() => String(route.params.slug ?? ''))

const { data: catalog } = await useDeviceCatalog()
const tabs = useDeviceTabs(catalog)

const models = computed(() => (
  (catalog.value?.devices ?? []).filter(device => device.brand?.slug === slug.value)
))

const brand = computed(() => (
  models.value[0]?.brand?.name ?? slug.value.replace(/-/g, ' ').toUpperCase()
))

const network = computed(() => models.value[0]?.category?.network ?? 'cdma')
const activeTab = computed(() => String(models.value[0]?.category?.id ?? ''))

useSeo({ title: () => brand.value })

function escapeHtml(value: string): string {
  return value.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
}

const heroTitle = computed(() => t('devices.hero_brand_title').replace('{brand}', escapeHtml(brand.value)))
const heroSubtitle = computed(() => t('devices.hero_brand_subtitle').replace('{brand}', brand.value))
const modelsCount = computed(() => t('devices.models_count').replace('{n}', String(models.value.length)))
</script>

<template>
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
                  <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">{{ brand }}</span>
              </nav>
              <p class="page-hero__eyebrow page-hero__eyebrow_silver">{{ t('seo.devices') }}</p>
              <h1 class="page-hero__title section__title" v-html="heroTitle"></h1>
              <p class="page-hero__subtitle">{{ heroSubtitle }}</p>
          </div>
      </div>
  </section>

<section class="devices">
      <div class="container">
          <DeviceChips :tabs="tabs" :active="activeTab" linked />

          <div class="callout">
              <p v-html="t(network === 'cdma' ? 'devices.callout_cdma' : 'devices.callout_5g')"></p>
          </div>

          <div class="brand-head">
              <NuxtLink class="brand-head__back" :to="localePath('/devices')"
                  :aria-label="t('devices.all_brands')">
                  <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                      <path d="M20 12H6M12 6l-6 6 6 6" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
              </NuxtLink>
              <h2 class="brand-head__title">{{ brand }}</h2>
              <span class="brand-head__count">{{ modelsCount }}</span>
          </div>

          <ul v-if="models.length" class="device-grid">
              <DeviceCard v-for="device in models" :key="device.slug" :device="device"
                  :label="t('devices.model')" />
          </ul>

          <p v-else class="devices__note">{{ t('devices.empty') }}</p>

          <p v-if="models.length" class="devices__note">{{ t('devices.note_brand') }}</p>
      </div>
  </section>
</template>
