<script setup lang="ts">
import type { DeviceInstallment } from '~/composables/useDevices'

const props = defineProps<{
  open: boolean
  name: string
  price: string
  offers: DeviceInstallment[]
}>()

const emit = defineEmits<{ close: [] }>()

const t = useT()

/**
 * Every partner keeps its own term while the window is open, so switching
 * one does not reset the figures the visitor is comparing it against.
 */
const chosen = ref<Record<string, number>>({})

watch(() => props.offers, (offers) => {
  chosen.value = Object.fromEntries(
    offers.map(offer => [offer.partner.slug, offer.options[0]?.term ?? 0]),
  )
}, { immediate: true })

function option(offer: DeviceInstallment) {
  return offer.options.find(item => item.term === chosen.value[offer.partner.slug]) ?? offer.options[0]
}

function money(value: number): string {
  return value.toLocaleString('ru-RU')
}

function onKeydown(event: KeyboardEvent): void {
  if (event.key === 'Escape' && props.open) {
    emit('close')
  }
}

watch(() => props.open, (open) => {
  document.body.classList.toggle('overflow__hidden', open)
})

onMounted(() => document.addEventListener('keydown', onKeydown))

onBeforeUnmount(() => {
  document.removeEventListener('keydown', onKeydown)
  document.body.classList.remove('overflow__hidden')
})
</script>

<template>
  <div class="pay-modal" :class="open && 'pay-modal_open'" :aria-hidden="open ? 'false' : 'true'">
    <div class="pay-modal__overlay" @click="emit('close')"></div>

    <div v-if="open" class="pay-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="installmentName">
      <button type="button" class="pay-modal__close" :aria-label="t('tariff.close', 'Закрыть')" @click="emit('close')">
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
        </svg>
      </button>

      <div class="pay-modal__head">
        <h3 id="installmentName" class="pay-modal__name">{{ name }}</h3>
        <p class="pay-modal__price">{{ price }} {{ t('devices.currency') }}</p>
      </div>

      <p v-if="!offers.length" class="pay-modal__empty">
        {{ t('devices.installment_empty', 'Вариантов рассрочки пока нет') }}
      </p>

      <ul v-else class="pay-modal__list">
        <li v-for="offer in offers" :key="offer.partner.slug" class="pay-offer">
          <div class="pay-offer__head">
            <img v-if="offer.partner.logo" class="pay-offer__logo" :src="offer.partner.logo"
                :alt="offer.partner.name" loading="lazy" />
            <h4 class="pay-offer__name">{{ offer.partner.name }}</h4>
          </div>

          <div class="pay-offer__body">
            <div class="select pay-offer__select">
              <select v-model.number="chosen[offer.partner.slug]" class="select__control"
                  :aria-label="t('devices.installment_term', 'Срок рассрочки')">
                <option v-for="item in offer.options" :key="item.term" :value="item.term">
                  {{ item.term }} {{ t('devices.installment_months', 'мес.') }}
                </option>
              </select>
              <svg class="select__chevron" viewBox="0 0 12 8" fill="none" aria-hidden="true">
                <path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                    stroke-linejoin="round" />
              </svg>
            </div>

            <dl class="pay-offer__figures">
              <div class="pay-offer__figure">
                <dt class="pay-offer__label">{{ t('devices.installment_monthly', 'Платёж в месяц') }}</dt>
                <dd class="pay-offer__value">{{ money(option(offer).monthly) }} {{ t('devices.currency') }}</dd>
              </div>
              <div class="pay-offer__figure">
                <dt class="pay-offer__label">{{ t('devices.installment_total', 'Общая сумма') }}</dt>
                <dd class="pay-offer__value pay-offer__value_total">{{ money(option(offer).total) }} {{ t('devices.currency') }}</dd>
              </div>
            </dl>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>
