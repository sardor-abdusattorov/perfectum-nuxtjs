<script setup lang="ts">
const props = defineProps<{ modelValue: string, placeholder?: string, label?: string }>()
const emit = defineEmits<{ 'update:modelValue': [string] }>()

const draft = ref(props.modelValue)

watch(() => props.modelValue, (value) => {
  draft.value = value
})

watch(draft, (value) => {
  if (value.trim() === '' && props.modelValue !== '') {
    emit('update:modelValue', '')
  }
})
</script>

<template>
  <form class="filter-search__field" role="search" @submit.prevent="emit('update:modelValue', draft.trim())">
    <input
      v-model="draft"
      type="search"
      class="filter-search__input"
      :placeholder="placeholder"
      :aria-label="label"
    />
    <button type="submit" class="filter-search__btn" :aria-label="label">
      <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8" />
        <path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
      </svg>
    </button>
  </form>
</template>
