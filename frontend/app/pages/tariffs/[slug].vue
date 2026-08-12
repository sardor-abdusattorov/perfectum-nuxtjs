<script setup lang="ts">
const route = useRoute()
const localePath = useLocalePath()
const t = useT()
const { open } = useTariffModal()

const slug = computed(() => String(route.params.slug ?? ''))
const { data: tariff } = await useTariff(slug)

if (!tariff.value) {
  throw createError({ statusCode: 404, statusMessage: 'Not Found', fatal: true })
}

useSeo({ title: () => tariff.value?.name ?? '' })

function connect(): void {
  if (!tariff.value) {
    return
  }

  open({
    name: tariff.value.name,
    price: tariff.value.price,
    price_currency: tariff.value.price_currency,
    price_period: tariff.value.price_period,
    modal_image: tariff.value.modal_image,
    buttons: tariff.value.buttons,
  })
}
</script>

<template>
  <template v-if="tariff">
    <section class="page-hero page-hero_single_tariff">
      <div class="container">
        <div class="page-hero__inner">
          <nav class="page-hero__crumbs" :aria-label="t('common.breadcrumbs')">
            <NuxtLink class="page-hero__crumb" :to="localePath('/')">{{ t('common.home') }}</NuxtLink>
            <svg class="page-hero__crumb-sep" viewBox="0 0 24 24" fill="none">
              <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <NuxtLink class="page-hero__crumb" :to="localePath('/tariffs')">{{ t('seo.tariffs') }}</NuxtLink>
            <svg class="page-hero__crumb-sep" viewBox="0 0 24 24" fill="none">
              <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">{{ tariff.name }}</span>
          </nav>

          <p class="page-hero__eyebrow page-hero__eyebrow_silver">{{ tariff.type?.name ?? '5G' }}</p>
          <h1 class="page-hero__title section__title">{{ tariff.name }}</h1>
        </div>
      </div>
    </section>

    <section class="tariff-detail">
      <div class="container">
        <article class="tariff-detail__card">
          <div class="tariff-detail__head">
            <span class="tariff-detail__tag"><b>{{ tariff.type?.name ?? '5G' }}</b></span>
          </div>

          <div class="tariff-detail__body">
            <div class="tariff-detail__top">
              <div class="tariff-detail__pricing">
                <span class="tariff-detail__brand">{{ tariff.name }}</span>
                <p class="tariff-detail__price">
                  <span class="tariff-detail__price-value">{{ tariff.price }}</span>
                  <span class="tariff-detail__price-currency">{{ tariff.price_currency }}</span>
                </p>
                <p class="tariff-detail__price-period">{{ tariff.price_period }}</p>
              </div>
              <button type="button" class="tariff-detail__connect" @click="connect()">
                {{ t('tariffs.connect') }}
              </button>
            </div>

            <TariffFeats v-if="tariff.features.length" tag="div" class="tariff-detail__feats" :features="tariff.features" />
          </div>
        </article>

        <details
          v-for="(description, index) in tariff.descriptions"
          :key="index"
          class="tariff-detail__more"
          :open="index === 0"
        >
          <summary class="tariff-detail__more-summary">
            {{ description.name }}
            <svg class="tariff-detail__more-chevron" viewBox="0 0 24 24" fill="none">
              <path d="M6 15l6-6 6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </summary>
          <div class="tariff-detail__more-content" v-html="description.content"></div>
        </details>

        <nav class="tariff-detail__switch" :aria-label="t('tariffs.nav_label')">
          <NuxtLink class="tariff-detail__switch-btn" :to="localePath('/tariffs')">
            {{ t('tariffs.all_tariffs') }}
          </NuxtLink>
          <NuxtLink class="tariff-detail__switch-btn tariff-detail__switch-btn_active" :to="localePath('/devices')">
            {{ t('tariffs.routers') }}
          </NuxtLink>
        </nav>
      </div>
    </section>
  </template>
</template>
