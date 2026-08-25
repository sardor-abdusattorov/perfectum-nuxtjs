<script setup lang="ts">
const preview = useCookie('preview')
const t = useT()
</script>

<template>
  <div v-if="preview" class="preview-bar" role="status">
    <div class="container preview-bar__row">
      <span class="preview-bar__text">
        {{ t('preview.notice', 'Режим предпросмотра') }}
      </span>
    </div>
  </div>
</template>
