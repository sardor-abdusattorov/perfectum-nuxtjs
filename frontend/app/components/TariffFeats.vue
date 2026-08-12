<script setup lang="ts">
import type { TariffFeature } from '~/composables/useTariffs'

defineProps<{ features: TariffFeature[], tag?: 'ul' | 'div' }>()
</script>

<template>
  <component :is="tag ?? 'ul'">
    <component
      :is="(tag ?? 'ul') === 'ul' ? 'li' : 'div'"
      v-for="(feature, index) in features"
      :key="index"
      class="tariff-feat"
      :class="index === features.length - 1 && 'tariff-feat_last'"
    >
      <span v-if="feature.icon" class="tariff-feat__icon">
        <img :src="`/images/icon-${feature.icon}.svg`" alt="" loading="lazy" />
      </span>
      <span class="tariff-feat__text">
        <b>{{ feature.title }}</b>
        <span v-if="feature.note" class="tariff-feat__muted">{{ feature.note }}</span>
      </span>
    </component>
  </component>
</template>
