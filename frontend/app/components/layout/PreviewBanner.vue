<script setup lang="ts">
const { active } = usePreview()
const t = useT()
</script>

<template>
  <div v-if="active" class="preview-bar" role="status">
    <div class="container preview-bar__row">
      <span class="preview-bar__text">
        {{ t('preview.notice', 'Режим просмотра') }}
      </span>
    </div>
  </div>
</template>
