<script setup lang="ts">
import type { MenuItem } from '~/types/api'

const props = defineProps<{ item: MenuItem, linkClass: string }>()

const { external, to } = useLink(() => props.item.url)
</script>

<template>
  <a
    v-if="external && to"
    :class="linkClass"
    :href="to"
    :target="item.target ?? undefined"
    :rel="item.target ? 'noopener' : undefined"
  >{{ item.name }}</a>

  <NuxtLink
    v-else-if="to"
    :class="linkClass"
    :to="to"
    :target="item.target ?? undefined"
    :rel="item.target ? 'noopener' : undefined"
  >{{ item.name }}</NuxtLink>

  <a v-else :class="linkClass" href="#" @click.prevent>{{ item.name }}</a>
</template>
