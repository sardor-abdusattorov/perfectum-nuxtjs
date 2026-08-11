<script setup lang="ts">
const props = defineProps<{ value: unknown }>()

const parts = computed(() => lines(props.value))
</script>

<template>
  <template v-for="(line, i) in parts" :key="i"><br v-if="i" />{{ line }}</template>
</template>
