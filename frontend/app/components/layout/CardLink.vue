<script setup lang="ts">
const props = defineProps<{ url?: string | null }>()

const localePath = useLocalePath()

const external = computed(() => /^(https?:)?\/\/|^(mailto|tel):/.test(props.url ?? ''))

const to = computed(() => {
  const url = props.url ?? ''

  if (!url) {
    return null
  }

  if (external.value) {
    return url
  }

  const [path, hash] = url.split('#')

  return localePath(path || '/') + (hash ? `#${hash}` : '')
})
</script>

<template>
  <a v-if="external && to" :href="to" target="_blank" rel="noopener"><slot /></a>
  <NuxtLink v-else-if="to" :to="to"><slot /></NuxtLink>
  <div v-else><slot /></div>
</template>
