<script setup lang="ts">
const props = defineProps<{
  link?: { label?: string | null, url?: string | null } | null
  linkClass?: string
}>()

const label = computed(() => props.link?.label ?? '')
const url = computed(() => props.link?.url ?? '')
</script>

<template>
  <LayoutCardLink v-if="label" :url="url" :class="linkClass">
    {{ label }}
    <slot />
  </LayoutCardLink>
</template>
