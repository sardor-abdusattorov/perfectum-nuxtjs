<script setup lang="ts">
defineProps<{ files: string[], title: string }>()

function fileName(url: string): string {
  return decodeURIComponent(url.split('/').pop() ?? url)
}
</script>

<template>
  <div v-if="files.length" class="file-list">
    <h2 class="file-list__title">{{ title }}</h2>
    <ul class="file-list__items">
      <li v-for="file in files" :key="file">
        <a class="file-list__item" :href="storageUrl(file)" target="_blank" rel="noopener">
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8l-5-5Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" />
            <path d="M14 3v5h5" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" />
          </svg>
          {{ fileName(file) }}
        </a>
      </li>
    </ul>
  </div>
</template>

<style scoped>
.file-list {
  margin-top: 32px;
  padding-top: 24px;
  border-top: 1px solid rgba(0, 0, 0, 0.1);
}

.file-list__title {
  margin-bottom: 16px;
  font-size: 18px;
  font-weight: 700;
  color: var(--color-black);
}

.file-list__items {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.file-list__item {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  font-size: 16px;
  color: var(--color-red);
  overflow-wrap: anywhere;
}

.file-list__item svg {
  flex: none;
  width: 20px;
  height: 20px;
}

.file-list__item:hover {
  text-decoration: underline;
}
</style>
