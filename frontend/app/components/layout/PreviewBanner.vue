<script setup lang="ts">
const { active, token, path } = usePreview()
const t = useT()

function leave(): void {
  token.value = null
  path.value = null

  reloadNuxtApp({ force: true })
}
</script>

<template>
  <div v-if="active" class="preview-bar" role="status">
    <div class="container preview-bar__row">
      <span class="preview-bar__badge">
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M2.5 12s3.5-6.5 9.5-6.5S21.5 12 21.5 12s-3.5 6.5-9.5 6.5S2.5 12 2.5 12Z" stroke="currentColor" stroke-width="1.7" />
          <circle cx="12" cy="12" r="2.75" stroke="currentColor" stroke-width="1.7" />
        </svg>
        {{ t('preview.notice', 'Режим просмотра') }}
      </span>

      <span class="preview-bar__hint">
        {{ t('preview.hint', 'Черновик виден только вам по ссылке из админки') }}
      </span>

      <button type="button" class="preview-bar__exit" @click="leave">
        {{ t('preview.exit', 'Выйти') }}
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" />
        </svg>
      </button>
    </div>
  </div>
</template>
