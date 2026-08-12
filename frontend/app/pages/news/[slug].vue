<script setup lang="ts">
const route = useRoute()
const localePath = useLocalePath()
const t = useT()

const slug = computed(() => String(route.params.slug ?? ''))
const { data: item } = await useNewsItem(slug)

if (!item.value) {
  throw createError({ statusCode: 404, statusMessage: 'Not Found', fatal: true })
}

useSeo({ title: () => item.value?.title ?? '' })
</script>

<template>
  <section v-if="item" class="article">
    <div class="container">
      <div class="article__inner">
        <div class="article__topline">
          <NuxtLink class="article__back" :to="localePath('/news')">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M19 12H5M11 6l-6 6 6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            {{ t('news.back') }}
          </NuxtLink>
          <span v-if="item.category" class="article__cat article__cat_red">{{ item.category.name }}</span>
        </div>

        <h1 class="article__title">{{ item.title }}</h1>
        <p class="article__date">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M3.875 8.46875H19.625M5.91071 2V3.68771M17.375 2V3.6875M17.375 3.6875H6.125C4.26104 3.6875 2.75 5.19854 2.75 7.0625V18.3126C2.75 20.1766 4.26104 21.6876 6.125 21.6876H17.375C19.239 21.6876 20.75 20.1766 20.75 18.3126L20.75 7.0625C20.75 5.19854 19.239 3.6875 17.375 3.6875ZM6.6875 12.4063H16.8125M6.6875 16.9063H16.8125" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          {{ dateShort(item.published_at) }}
        </p>

        <div
          v-if="item.image"
          class="article__cover"
          role="img"
          :aria-label="item.title"
          :style="{ backgroundImage: `url(${item.image})`, backgroundSize: 'cover', backgroundPosition: 'center' }"
        ></div>
        <div v-else class="article__cover" aria-hidden="true"></div>

        <div class="article__body" v-html="item.content"></div>
      </div>
    </div>
  </section>
</template>
