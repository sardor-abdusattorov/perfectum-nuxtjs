<script setup lang="ts">
defineProps<{ icon: string | null }>()
</script>

<template>
  <img v-if="icon" :src="icon" alt="" loading="lazy" />
  <svg v-else viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <path d="M17.5 3.5 6.5 18h6.7l-2.7 10.5L21.5 14h-6.7l2.7-10.5Z" stroke="currentColor"
          stroke-width="2.2" stroke-linejoin="round" />
  </svg>
</template>
