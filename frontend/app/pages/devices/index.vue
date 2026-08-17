<script setup lang="ts">
const localePath = useLocalePath()
const route = useRoute()
const t = useT()

useSeo({ page: 'devices', titleKey: 'seo.devices' })

const { data: catalog } = await useDeviceCatalog()
const tabs = useDeviceTabs(catalog)

const active = ref(typeof route.query.tab === 'string' ? route.query.tab : '')

watchEffect(() => {
  if (tabs.value.length && !tabs.value.some(tab => tab.key === active.value)) {
    active.value = tabs.value[0]!.key
  }
})

const visible = computed(() => {
  const devices = catalog.value?.devices ?? []

  if (active.value === 'stock') {
    return devices.filter(device => device.in_stock)
  }

  return devices.filter(device => String(device.category?.id) === active.value)
})

const isCdma = computed(() => (
  catalog.value?.categories.find(category => String(category.id) === active.value)?.network === 'cdma'
))

/**
 * The CDMA compatibility list is browsed brand by brand, the way the old site
 * did; the small 5G lists go straight to the device cards.
 */
const brands = computed(() => {
  const map = new Map<string, { name: string, count: number }>()

  for (const device of visible.value) {
    const name = device.brand?.trim()

    if (!name) {
      continue
    }

    const key = brandSlug(name)
    const entry = map.get(key) ?? { name, count: 0 }

    entry.count += 1
    map.set(key, entry)
  }

  return [...map.entries()]
    .map(([slug, entry]) => ({ slug, ...entry }))
    .sort((a, b) => a.name.localeCompare(b.name))
})

const BRAND_LOOKS: Record<string, { logo?: string, tone?: string, mark?: string }> = {
  'amgoo': { tone: 'amgoo', mark: '<b>AM</b>GOO' },
  'apple': { logo: 'apple.svg' },
  'artel': { logo: 'artel.svg' },
  'audiovox': { tone: 'blue' },
  'blackberry': { logo: 'blackberry.svg' },
  'bless': { tone: 'red' },
  'franklin-wireless': { tone: 'blue' },
  'hisense': { tone: 'blue' },
  'htc': { logo: 'htc.svg' },
  'huawei': { tone: 'red' },
  'kyocera': { tone: 'red' },
}
</script>

<template>
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
                  <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">{{ t('seo.devices') }}</span>
              </nav>
              <p class="page-hero__eyebrow page-hero__eyebrow_silver">{{ t('devices.hero_eyebrow') }}</p>
              <h1 class="page-hero__title section__title" v-html="t('devices.hero_title')"></h1>
              <p class="page-hero__subtitle">{{ t('devices.hero_subtitle') }}</p>
          </div>
      </div>
  </section>

  <!-- DEVICES -->
  <section class="devices">
      <div class="container">
          <DeviceChips :tabs="tabs" :active="active" @select="active = $event" />

          <div class="callout">
              <p v-html="t(isCdma ? 'devices.callout_cdma' : 'devices.callout_5g')"></p>
          </div>

          <ul v-if="isCdma && brands.length" class="brand-grid">
              <li v-for="brand in brands" :key="brand.slug" class="brand-card">
                  <img v-if="BRAND_LOOKS[brand.slug]?.logo" class="brand-card__logo"
                      :src="`/images/brands/${BRAND_LOOKS[brand.slug]!.logo}`" :alt="brand.name"
                      loading="lazy" />
                  <span v-else-if="BRAND_LOOKS[brand.slug]?.mark" class="brand-card__name"
                      :class="`brand-card__name_${BRAND_LOOKS[brand.slug]!.tone}`"
                      v-html="BRAND_LOOKS[brand.slug]!.mark"></span>
                  <span v-else class="brand-card__name"
                      :class="BRAND_LOOKS[brand.slug]?.tone && `brand-card__name_${BRAND_LOOKS[brand.slug]!.tone}`">{{ brand.name }}</span>
                  <NuxtLink class="brand-card__link" :to="localePath(`/devices/brands/${brand.slug}`)"
                      :aria-label="brand.name" />
              </li>
          </ul>

          <ul v-else-if="visible.length" class="device-grid">
              <DeviceCard v-for="device in visible" :key="device.slug" :device="device" />
          </ul>

          <p v-else class="devices__note">{{ t('devices.empty') }}</p>

          <p v-if="visible.length" class="devices__note">{{ t('devices.note') }}</p>
      </div>
  </section>
</template>
