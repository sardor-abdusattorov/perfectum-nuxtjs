<script setup lang="ts">
const props = defineProps<{ page: number, pages: number }>()
const emit = defineEmits<{ change: [page: number] }>()

const t = useT()

const window = computed<Array<number | null>>(() => {
  const total = props.pages
  const current = props.page

  if (total <= 7) {
    return Array.from({ length: total }, (_, index) => index + 1)
  }

  const items: Array<number | null> = [1]
  const left = Math.max(2, current - 1)
  const right = Math.min(total - 1, current + 1)

  if (left > 2) {
    items.push(null)
  }

  for (let index = left; index <= right; index += 1) {
    items.push(index)
  }

  if (right < total - 1) {
    items.push(null)
  }

  items.push(total)

  return items
})

function go(target: number): void {
  if (target >= 1 && target <= props.pages && target !== props.page) {
    emit('change', target)
  }
}
</script>

<template>
  <nav v-if="pages > 1" class="pagination" :aria-label="t('common.pagination')">
    <button type="button" class="pagination__arrow" :aria-label="t('common.prev')" @click="go(page - 1)">
      <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
    </button>
    <div class="pagination__pages">
      <template v-for="(item, index) in window" :key="index">
        <span v-if="item === null" class="pagination__page">…</span>
        <button
          v-else
          type="button"
          class="pagination__page"
          :class="item === page && 'pagination__page_active'"
          :aria-current="item === page ? 'page' : undefined"
          @click="go(item)"
        >{{ item }}</button>
      </template>
    </div>
    <button type="button" class="pagination__arrow" :aria-label="t('common.next')" @click="go(page + 1)">
      <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
    </button>
  </nav>
</template>
