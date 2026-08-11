<script setup lang="ts">
defineProps<{
  link?: { label?: string | null, url?: string | null } | null
  linkClass?: string
}>()
</script>

<template>
  <LayoutCardLink v-if="link?.label" :url="link.url" :class="linkClass">
    {{ link.label }}
    <slot />
  </LayoutCardLink>
</template>
