<script setup lang="ts">
import type { Taxonomy } from '~/composables/useTariffs'
import type { ApiResponse } from '~/types/api'

const localePath = useLocalePath()
const t = useT()
const { locale } = useI18n()
const { $api } = useNuxtApp()

useSeo({ titleKey: 'seo.help_contact' })

const MESSAGE_LIMIT = 500

const { data: themes } = await useAsyncData(
  'application-themes',
  () => $api<ApiResponse<Taxonomy[]>>('/categories/application-themes').then(response => response.data),
  { watch: [locale], default: () => [] as Taxonomy[] },
)

const phone = ref('')
const theme = ref<number | ''>('')
const message = ref('')
const errors = reactive({ phone: false, theme: false, message: false })

const sending = ref(false)
const toast = ref<{ kind: 'success' | 'error', text: string } | null>(null)
let toastTimer: ReturnType<typeof setTimeout> | undefined

const left = computed(() => MESSAGE_LIMIT - message.value.length)

const agree = computed(() => rich(t('help.contact_agree')).replace(
  '<a>',
  `<a href="${localePath('/documents')}">`,
))

function phoneDigits(): string {
  return phone.value.replace(/\D/g, '')
}

/**
 * The rendered value always opens with the +998 literal, so its digits are
 * dropped before counting; a pasted international number sheds its country
 * code too, while a bare local number starting with 99 stays intact.
 */
function onPhone(event: Event): void {
  const input = event.target as HTMLInputElement
  let digits = input.value.replace(/\D/g, '')

  if (input.value.startsWith('+998') || (digits.length > 9 && digits.startsWith('998'))) {
    digits = digits.slice(3)
  }

  digits = digits.slice(0, 9)

  let value = ''

  if (digits.length) {
    value = `+998 (${digits.slice(0, 2)}`
  }
  if (digits.length > 2) {
    value += `) ${digits.slice(2, 5)}`
  }
  if (digits.length > 5) {
    value += `-${digits.slice(5, 7)}`
  }
  if (digits.length > 7) {
    value += `-${digits.slice(7, 9)}`
  }

  phone.value = value
  input.value = value

  if (digits.length === 9) {
    errors.phone = false
  }
}

function notify(kind: 'success' | 'error', text: string): void {
  toast.value = { kind, text }
  clearTimeout(toastTimer)
  toastTimer = setTimeout(() => (toast.value = null), 6000)
}

async function submit(): Promise<void> {
  errors.phone = !/^998\d{9}$/.test(phoneDigits())
  errors.theme = !theme.value
  errors.message = !message.value.trim()

  if (errors.phone || errors.theme || errors.message || sending.value) {
    return
  }

  sending.value = true

  try {
    await $api('/applications', {
      method: 'POST',
      body: { phone: phoneDigits(), theme: theme.value, message: message.value.trim() },
    })

    phone.value = ''
    theme.value = ''
    message.value = ''
    notify('success', t('help.contact_success'))
  }
  catch {
    notify('error', t('help.contact_error'))
  }
  finally {
    sending.value = false
  }
}
</script>

<template>
  <section class="page-hero page-hero_inner page-hero_help">
    <div class="container">
      <div class="page-hero__inner">
        <nav class="page-hero__crumbs" :aria-label="t('common.breadcrumbs')">
          <NuxtLink class="page-hero__crumb" :to="localePath('/')">{{ t('common.home') }}</NuxtLink>
          <svg class="page-hero__crumb-sep" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M4 12h14M12 6l6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <span class="page-hero__crumb page-hero__crumb_current" aria-current="page">{{ t('help.nav_contact') }}</span>
        </nav>
        <h1 class="page-hero__title section__title" v-html="rich(t('help.contact_title'), { accent: 'page-hero__title-red' })"></h1>
      </div>
    </div>
  </section>

  <section class="help">
    <div class="container">
      <div class="help__panel">
        <HelpAside active="contact" />

        <div class="help__content">
          <div class="help__tab help__tab_active">
            <h2 class="help__tab-title">{{ t('help.contact_form_title') }}</h2>

            <form class="help__form" novalidate @submit.prevent="submit()">
              <div class="field">
                <label class="field__label field__label_dark" for="help-phone">{{ t('help.contact_phone') }}</label>
                <input
                  id="help-phone"
                  type="tel"
                  class="field__input"
                  :class="errors.phone && 'field__input_invalid'"
                  placeholder="+998 (__) ___-__-__"
                  :value="phone"
                  @input="onPhone"
                />
                <p v-if="errors.phone" class="field__error">{{ t('help.contact_required') }}</p>
              </div>

              <div class="field">
                <label class="field__label field__label_dark" for="help-topic">{{ t('help.contact_theme') }}</label>
                <div class="select">
                  <select
                    id="help-topic"
                    v-model="theme"
                    class="select__control"
                    :class="errors.theme && 'field__input_invalid'"
                    @change="errors.theme = false"
                  >
                    <option value="" disabled>{{ t('help.contact_theme_placeholder') }}</option>
                    <option v-for="item in themes" :key="item.id" :value="item.id">{{ item.name }}</option>
                  </select>
                  <svg class="select__chevron" viewBox="0 0 12 8" fill="none" aria-hidden="true">
                    <path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </div>
                <p v-if="errors.theme" class="field__error">{{ t('help.contact_required') }}</p>
              </div>

              <div class="field">
                <label class="field__label field__label_dark" for="help-message">{{ t('help.contact_message') }}</label>
                <textarea
                  id="help-message"
                  v-model="message"
                  class="field__textarea"
                  :class="errors.message && 'field__input_invalid'"
                  :placeholder="t('help.contact_message_placeholder')"
                  :maxlength="MESSAGE_LIMIT"
                  rows="5"
                  @input="errors.message = false"
                ></textarea>
                <p v-if="errors.message" class="field__error">{{ t('help.contact_required') }}</p>
              </div>

              <p class="help__counter">{{ t('help.contact_counter') }}: <b>{{ left }}</b></p>

              <button type="submit" class="help__submit" :disabled="sending">{{ t('help.contact_submit') }}</button>

              <p class="help__agree" v-html="agree"></p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <Transition name="toast">
    <div v-if="toast" class="toast" :class="`toast_${toast.kind}`" role="status">
      <svg v-if="toast.kind === 'success'" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.8" />
        <path d="M8 12.5l2.5 2.5L16 9.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
      <svg v-else viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.8" />
        <path d="M12 7v6M12 16.5v.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
      </svg>
      <span>{{ toast.text }}</span>
    </div>
  </Transition>
</template>
