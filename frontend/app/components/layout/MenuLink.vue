<script setup lang="ts">
import type { MenuItem } from '~/types/api'

const props = defineProps<{ item: MenuItem, linkClass: string }>()

const localePath = useLocalePath()

const external = computed(() => /^(https?:)?\/\/|^(mailto|tel):/.test(props.item.url ?? ''))

const to = computed(() => {
  const url = props.item.url ?? ''

  if (external.value) {
    return url
  }

  const [path, hash] = url.split('#')

  return localePath(path || '/') + (hash ? `#${hash}` : '')
})
</script>

<template>
  <a
    v-if="external"
    :class="linkClass"
    :href="to"
    :target="item.target ?? undefined"
    :rel="item.target ? 'noopener' : undefined"
  >{{ item.name }}</a>

  <NuxtLink
    v-else-if="item.url"
    :class="linkClass"
    :to="to"
    :target="item.target ?? undefined"
    :rel="item.target ? 'noopener' : undefined"
  >{{ item.name }}</NuxtLink>

  <a v-else :class="linkClass" href="#" @click.prevent>{{ item.name }}</a>
</template>
