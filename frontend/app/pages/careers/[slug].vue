<script setup lang="ts">
const route = useRoute()
const localePath = useLocalePath()
const t = useT()

const slug = computed(() => String(route.params.slug ?? ''))
const { data: item } = await useVacancyItem(slug)

if (!item.value) {
  throw createError({ statusCode: 404, statusMessage: 'Not Found', fatal: true })
}

useSeo({ title: () => item.value?.title ?? '' })
</script>

<template>
  <section v-if="item" class="vacancy">
    <div class="container">
      <div class="vacancy__inner">
        <NuxtLink class="vacancy__back" :to="localePath('/careers')">
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" /></svg>
          {{ t('careers.back') }}
        </NuxtLink>

        <div class="vacancy__hero">
          <span v-if="item.department" class="vacancy__badge">{{ item.department }}</span>
          <h1 class="vacancy__title">{{ item.title }}</h1>
        </div>

        <article class="vacancy__card">
          <div class="vacancy__section rich" v-html="item.content"></div>

          <div class="vacancy__cta">
            <p class="vacancy__cta-line">{{ t('careers.cta_line') }}</p>
            <p class="vacancy__cta-line" v-html="t('careers.cta_contacts')"></p>
          </div>
        </article>
      </div>
    </div>
  </section>
</template>
