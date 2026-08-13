<script setup lang="ts">
const route = useRoute()
const localePath = useLocalePath()
const t = useT()

const slug = computed(() => String(route.params.slug ?? ''))
const { data: item } = await useActionItem(slug)

if (!item.value) {
  throw createError({ statusCode: 404, statusMessage: 'Not Found', fatal: true })
}

useSeo({ title: () => item.value?.title ?? '' })

const period = computed(() => {
  if (!item.value?.starts_at) {
    return item.value?.ends_at ? `${dateShort(item.value.ends_at)}` : ''
  }

  return item.value.ends_at
    ? `${dateShort(item.value.starts_at)} — ${dateShort(item.value.ends_at)}`
    : dateShort(item.value.starts_at)
})
</script>

<template>
  <section v-if="item" class="article">
    <div class="container">
      <div class="article__inner">
        <NuxtLink class="article__back" :to="localePath('/actions')">
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M19 12H5M11 6l-6 6 6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          {{ t('actions.back') }}
        </NuxtLink>

        <div class="article__hero">
          <span v-if="item.category" class="article__hero-cat">{{ item.category.name }}</span>
          <h1 class="article__hero-title">{{ item.title }}</h1>
        </div>

        <div
          v-if="item.main_image"
          class="article__cover article__cover_photo"
          role="img"
          :aria-label="item.title"
          :style="cover(item.main_image)"
        ></div>

        <div class="article__card">
          <div class="article__meta">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M3.875 8.46875H19.625M5.91071 2V3.68771M17.375 2V3.6875M17.375 3.6875H6.125C4.26104 3.6875 2.75 5.19854 2.75 7.0625V18.3126C2.75 20.1766 4.26104 21.6876 6.125 21.6876H17.375C19.239 21.6876 20.75 20.1766 20.75 18.3126L20.75 7.0625C20.75 5.19854 19.239 3.6875 17.375 3.6875ZM6.6875 12.4063H16.8125M6.6875 16.9063H16.8125" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span v-if="period" class="article__meta-date">{{ period }}</span>
            <span v-if="item.badge" class="article__meta-status">{{ item.badge }}</span>
          </div>
          <div class="article__body" v-html="item.content"></div>
        </div>
      </div>
    </div>
  </section>
</template>
