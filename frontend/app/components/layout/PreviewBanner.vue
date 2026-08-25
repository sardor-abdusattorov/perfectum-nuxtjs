<script setup lang="ts">
const preview = useCookie('preview')
const t = useT()

/**
 * A draft looks exactly like a published page, which is the point — and the
 * reason to say plainly that nobody else can see it, so an editor never takes
 * the preview for the live site and leaves it unpublished.
 *
 * Leaving drops the cookie and reloads: the page is then answered the way any
 * visitor gets it — for a draft, that is a 404.
 */
function leave(): void {
  preview.value = null
  window.location.reload()
}
</script>

<template>
  <div v-if="preview" class="preview-bar" role="status">
    <div class="container preview-bar__row">
      <span class="preview-bar__text">
        {{ t('preview.notice', 'Режим предпросмотра — черновики видны только вам') }}
      </span>

      <button type="button" class="preview-bar__close" @click="leave()">
        {{ t('preview.exit', 'Выйти') }}
      </button>
    </div>
  </div>
</template>
