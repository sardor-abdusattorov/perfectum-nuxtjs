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

function fileName(url: string): string {
  return decodeURIComponent(url.split('/').pop() ?? url)
}
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

            <div v-if="service.files?.length" class="article__files">
              <h2 class="article__files-title">{{ t('services.files', 'Файлы') }}</h2>
              <ul class="article__files-list">
                <li v-for="file in service.files" :key="file">
                  <a class="article__file" :href="storageUrl(file)" target="_blank" rel="noopener">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8l-5-5Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" />
                      <path d="M14 3v5h5" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" />
                    </svg>
                    {{ fileName(file) }}
                  </a>
                </li>
              </ul>
            </div>
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

.article__files {
  margin-top: 32px;
  padding-top: 24px;
  border-top: 1px solid rgba(0, 0, 0, 0.1);
}

.article__files-title {
  margin-bottom: 16px;
  font-size: 18px;
  font-weight: 700;
  color: var(--color-black);
}

.article__files-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.article__file {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  font-size: 16px;
  color: var(--color-red);
  overflow-wrap: anywhere;
}

.article__file svg {
  flex: none;
  width: 20px;
  height: 20px;
}

.article__file:hover {
  text-decoration: underline;
}
</style>
