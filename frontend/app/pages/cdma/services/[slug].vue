<script setup lang="ts">
import type { ServiceFact } from '~/composables/useServices'

const localePath = useLocalePath()
const route = useRoute()
const t = useT()

definePageMeta({ layout: 'cdma' })

const slug = computed(() => String(route.params.slug ?? ''))

const { data: service } = await useService(slug)

if (!service.value) {
  throw createError({ statusCode: 404, statusMessage: 'Not Found', fatal: true })
}

useSeo({ title: () => service.value?.name ?? '' })

/**
 * The price and the USSD code always open the fact list; the admin's own
 * facts follow them.
 */
const facts = computed<ServiceFact[]>(() => {
  const entry = service.value

  if (!entry) {
    return []
  }

  return [
    ...(entry.price ? [{ label: t('services.price_label'), value: entry.price }] : []),
    ...(entry.ussd ? [{ label: t('services.ussd_label'), value: entry.ussd }] : []),
    ...entry.facts,
  ]
})
</script>

<template>
  <template v-if="service">
      <!-- CDMA HERO -->
      <section class="cdma-hero cdma-hero_slim">
          <div class="container">
              <nav class="cdma-crumbs" :aria-label="t('common.breadcrumbs')">
                  <NuxtLink class="cdma-crumbs__link" :to="localePath('/cdma')">CDMA</NuxtLink>
                  <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <NuxtLink class="cdma-crumbs__link" :to="localePath('/cdma') + '#cdma-services'">{{ t('cdma.services_title') }}</NuxtLink>
                  <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <span class="cdma-crumbs__current">{{ service.name }}</span>
              </nav>
              <h1 class="cdma-hero__title">{{ service.name }}</h1>
          </div>
      </section>

      <!-- CDMA SERVICE -->
      <section class="cdma-detail">
          <div class="container">
              <span v-if="service.category" class="cdma-badge">{{ service.category.name }}</span>
              <p v-if="service.lead || service.excerpt" class="cdma-detail__lead" v-html="rich(service.lead ?? service.excerpt)"></p>

              <ul v-if="facts.length" class="cdma-facts">
                  <li v-for="(fact, index) in facts" :key="index" class="cdma-fact">
                      <span class="cdma-fact__label">{{ fact.label }}</span>
                      <span class="cdma-fact__value">{{ fact.value }}</span>
                  </li>
              </ul>

              <template v-if="service.steps.length">
                  <h2 class="cdma-detail__subhead">{{ t('services.steps_title') }}</h2>
                  <ol class="cdma-steps">
                      <li v-for="(step, index) in service.steps" :key="index" class="cdma-step">
                          <span class="cdma-step__num" aria-hidden="true">{{ index + 1 }}</span>
                          <div class="cdma-step__body">
                              <p class="cdma-step__text">{{ step.text }}</p>
                              <span v-if="step.code" class="cdma-step__code">{{ step.code }}</span>
                          </div>
                      </li>
                  </ol>
              </template>

              <template v-if="service.content">
                  <h2 class="cdma-detail__subhead">{{ t('services.description_title') }}</h2>
                  <div class="cdma-prose rich" v-html="service.content"></div>
              </template>

              <NuxtLink class="cdma-back" :to="localePath('/cdma') + '#cdma-services'">
                  <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M19 12H5M11 18l-6-6 6-6" stroke="currentColor" stroke-width="1.8"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  {{ t('services.all_services') }}
              </NuxtLink>
          </div>
      </section>

      <!-- CDMA FOOTER SECTION -->
      <CdmaSectionFooter />
  </template>
</template>
