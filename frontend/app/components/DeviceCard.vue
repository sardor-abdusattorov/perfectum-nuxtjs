<script setup lang="ts">
import type { Device } from '~/composables/useDevices'

const props = defineProps<{ device: Device, label?: string }>()

const localePath = useLocalePath()
const t = useT()

const price = computed(() => (
  props.device.price == null ? '' : props.device.price.toLocaleString('ru-RU')
))
</script>

<template>
  <li class="device-card">
      <div class="device-card__media">
          <img v-if="device.image" class="device-card__photo" :src="device.image" :alt="device.name"
              loading="lazy" />
          <DeviceSilhouette v-else />
      </div>
      <p class="device-card__label">{{ label ?? device.brand?.name ?? t('devices.model') }}</p>
      <h3 class="device-card__name">{{ device.name }}</h3>
      <p v-if="price" class="device-card__price">{{ price }} {{ t('devices.currency') }}</p>
      <NuxtLink class="device-card__link" :to="localePath(`/devices/${device.slug}`)"
          :aria-label="device.name" />
  </li>
</template>
