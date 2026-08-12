<script setup lang="ts">
import type { TariffButton } from '~/types/api'

const { current, close } = useTariffModal()
const t = useT()

const buttons = computed<TariffButton[]>(() => current.value?.buttons ?? [])

function href(button: TariffButton): string {
  return button.url || '#'
}

function external(button: TariffButton): boolean {
  return button.type !== 'tel' && /^(https?:)?\/\//.test(button.url ?? '')
}

function onKeydown(event: KeyboardEvent): void {
  if (event.key === 'Escape' && current.value) {
    close()
  }
}

watch(current, (tariff) => {
  document.body.classList.toggle('overflow__hidden', tariff !== null)
})

onMounted(() => document.addEventListener('keydown', onKeydown))

onBeforeUnmount(() => {
  document.removeEventListener('keydown', onKeydown)
  document.body.classList.remove('overflow__hidden')
})
</script>

<template>
  <div
    class="tariff-modal"
    :class="current && 'tariff-modal_open'"
    :aria-hidden="current ? 'false' : 'true'"
    @keydown.esc="close()"
  >
    <div class="tariff-modal__overlay" @click="close()"></div>

    <div
      v-if="current"
      class="tariff-modal__dialog"
      role="dialog"
      aria-modal="true"
      aria-labelledby="tariffModalName"
    >
      <button type="button" class="tariff-modal__close" :aria-label="t('tariff.close')" @click="close()">
        <svg viewBox="0 0 24 24" fill="none">
          <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
        </svg>
      </button>

      <div v-if="current.modal_image" class="tariff-modal__badge">
        <img class="tariff-modal__badge-img" :src="current.modal_image" :alt="current.name" />
      </div>

      <h3 id="tariffModalName" class="tariff-modal__name">{{ current.name }}</h3>

      <div class="tariff-modal__pricing">
        <span class="tariff-modal__price-value">{{ current.price }}</span>
        <span class="tariff-modal__price-currency">{{ current.price_currency }}</span>
        <span class="tariff-modal__price-period">{{ current.price_period }}</span>
      </div>

      <h2 class="tariff-modal__heading">{{ t('tariff.connect_anyway') }}:</h2>

      <div v-if="buttons.length" class="tariff-modal__buttons">
        <a
          v-for="(button, index) in buttons"
          :key="index"
          class="tariff-modal__btn"
          :href="href(button)"
          :target="external(button) ? '_blank' : undefined"
          :rel="external(button) ? 'noopener' : undefined"
        >
          <img v-if="button.icon" class="tariff-modal__btn-icon" :src="button.icon" alt="" />
          {{ button.name }}
        </a>
      </div>
    </div>
  </div>
</template>
