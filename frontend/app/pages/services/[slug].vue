<script setup lang="ts">
const localePath = useLocalePath()
const route = useRoute()
const t = useT()

const slug = computed(() => String(route.params.slug ?? ''))

const { data: service } = await useService(slug)

if (!service.value) {
  throw createError({ statusCode: 404, statusMessage: 'Not Found', fatal: true })
}

useSeo({ page: 'services', title: () => service.value?.name ?? '' })
</script>

<template>
  <section v-if="service" class="article">
    <div class="container">
      <div class="article__inner">
        <NuxtLink class="article__back" :to="localePath('/services')">
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M19 12H5M11 18l-6-6 6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          {{ t('services.all_services') }}
        </NuxtLink>

        <div class="article__hero">
          <h1 class="article__hero-title">{{ service.name }}</h1>
        </div>

        <div class="article__card">
          <img v-if="service.image" class="article__image" :src="service.image" :alt="service.name">

          <div class="article__content">
            <p v-if="service.lead"><b v-html="rich(service.lead)"></b></p>
            <p v-if="service.price"><b>{{ t('services.price_label') }}:</b> {{ service.price }}</p>
            <p v-if="service.ussd"><b>{{ t('services.ussd_label') }}:</b> {{ service.ussd }}</p>
            <div v-if="service.content" class="rich" v-html="service.content" />

            <FileList :files="service.files ?? []" :title="t('services.files', 'Файлы')" />
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.article__image {
  width: 100%;
  height: auto;
  border-radius: var(--radius);
}

</style>
