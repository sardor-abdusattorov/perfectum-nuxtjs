<script setup lang="ts">
import type { FaqItem } from '~/composables/useFaqs'

defineProps<{ items: FaqItem[], openFirst?: boolean }>()
</script>

<template>
  <div class="faq-accordion">
    <details
      v-for="(item, index) in items"
      :key="item.question"
      class="faq-item"
      :open="openFirst && index === 0"
    >
      <summary class="faq-item__summary">
        <span class="faq-item__question">{{ item.question }}</span>
        <span class="faq-item__toggle" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
          </svg>
        </span>
      </summary>
      <div class="faq-item__answer" v-html="item.answer"></div>
    </details>
  </div>
</template>
