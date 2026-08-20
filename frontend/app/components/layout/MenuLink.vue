<script setup lang="ts">
import type { MenuItem } from '~/types/api'

const props = defineProps<{ item: MenuItem, linkClass: string }>()

const { external, to } = useUrl(() => props.item.url)
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

  <!-- a heading that only gathers other pages: it opens the list, it does not
       lead anywhere, and it stays reachable from the keyboard -->
  <span v-else :class="linkClass" tabindex="0" role="button" aria-haspopup="true">{{ item.name }}</span>
</template>
