<script setup lang="ts">
import type { DeviceTab } from '~/composables/useDevices'

defineProps<{ tabs: DeviceTab[], active?: string, linked?: boolean }>()
defineEmits<{ select: [key: string] }>()

const localePath = useLocalePath()
const t = useT()
</script>

<template>
  <div v-if="tabs.length" class="filter-search">
      <div class="filter-search__chips" :role="linked ? undefined : 'tablist'"
          :aria-label="t('devices.tabs_label')">
          <template v-for="tab in tabs" :key="tab.key">
              <NuxtLink v-if="linked" class="filter-search__chip"
                  :class="tab.key === active && 'filter-search__chip_active'"
                  :to="localePath({ path: '/devices', query: { tab: tab.key } })">
                  {{ tab.label }} <span class="filter-search__chip-count">{{ tab.count }}</span>
              </NuxtLink>
              <button v-else type="button" class="filter-search__chip"
                  :class="tab.key === active && 'filter-search__chip_active'" role="tab"
                  :aria-selected="tab.key === active" @click="$emit('select', tab.key)">
                  {{ tab.label }} <span class="filter-search__chip-count">{{ tab.count }}</span>
              </button>
          </template>
      </div>
  </div>
</template>
