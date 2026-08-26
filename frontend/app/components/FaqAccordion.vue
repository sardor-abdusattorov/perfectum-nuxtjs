<script setup lang="ts">
import type { FaqItem } from '~/composables/useFaqs'

const props = defineProps<{ items: FaqItem[], openFirst?: boolean }>()

const uid = useId()
const open = ref(props.openFirst ? 0 : -1)

watch(() => props.items, () => {
  open.value = props.openFirst ? 0 : -1
})

function toggle(index: number) {
  open.value = open.value === index ? -1 : index
}
</script>

<template>
  <div class="faq-accordion">
    <div
      v-for="(item, index) in items"
      :key="item.question"
      class="faq-item"
      :class="index === open && 'faq-item_open'"
    >
      <button
        type="button"
        class="faq-item__summary"
        :aria-expanded="index === open"
        :aria-controls="`${uid}-${index}`"
        @click="toggle(index)"
      >
        <span class="faq-item__question">{{ item.question }}</span>
        <span class="faq-item__toggle" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
          </svg>
        </span>
      </button>
      <div :id="`${uid}-${index}`" class="faq-item__body" :inert="index !== open">
        <div class="faq-item__body-inner">
          <div class="faq-item__answer rich" v-html="item.answer"></div>
        </div>
      </div>
    </div>
  </div>
</template>
