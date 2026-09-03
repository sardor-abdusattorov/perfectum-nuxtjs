<script setup lang="ts">
defineProps<{ svg?: string | null }>()
</script>

<template>
  <span v-if="svg" class="contact-card__icon" aria-hidden="true" v-html="svg"></span>
</template>
