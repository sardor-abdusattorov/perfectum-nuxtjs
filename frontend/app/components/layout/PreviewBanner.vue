<script setup lang="ts">
const route = useRoute()
const t = useT()

/**
 * A draft looks exactly like a published page, which is the point — and the
 * reason to say plainly that nobody else can see it, so an editor never takes
 * the preview for the live site and leaves it unpublished.
 */
const previewing = computed(() => typeof route.query.preview === 'string' && route.query.preview !== '')
</script>

<template>
  <div v-if="previewing" class="preview-bar" role="status">
    <span class="preview-bar__dot" aria-hidden="true"></span>
    {{ t('preview.notice', 'Черновик — эту страницу видно только по этой ссылке') }}
  </div>
</template>
