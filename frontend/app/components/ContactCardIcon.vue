<script setup lang="ts">
/**
 * The card's mark comes from the admin's icon picker, rendered to SVG on the
 * server the same way the social icons arrive — the site only prints markup.
 */
defineProps<{ svg?: string | null }>()
</script>

<template>
  <span v-if="svg" class="contact-card__icon" aria-hidden="true" v-html="svg"></span>
</template>
