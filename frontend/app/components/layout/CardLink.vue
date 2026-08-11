<script setup lang="ts">
const props = defineProps<{ url?: string | null }>()

const { external, to } = useLink(() => props.url)
</script>

<template>
  <a v-if="external && to" :href="to" target="_blank" rel="noopener"><slot /></a>
  <NuxtLink v-else-if="to" :to="to"><slot /></NuxtLink>
  <div v-else><slot /></div>
</template>
