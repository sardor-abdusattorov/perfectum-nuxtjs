<script setup lang="ts">
const localePath = useLocalePath()
const menu = useMenu('footer')
const socials = useSocials()
const stores = useStores()
const setting = useSetting()
const t = useT()

const phones = computed(() => [setting('phone_primary'), setting('phone_secondary')].filter(Boolean))

const emails = computed(() => [
  { address: setting('email_info'), note: t('footer.email_info_note') },
  { address: setting('email_hotline'), note: t('footer.email_hotline_note') },
].filter(email => email.address))

const telegram = computed(() => {
  const handle = setting('telegram_url').split('?')[0]?.replace(/\/+$/, '').split('/').pop()

  return handle ? `@${handle}` : ''
})

function telHref(phone: string): string {
  return `tel:${phone.replace(/[^+\d]/g, '')}`
}
</script>

<template>
  <footer class="footer">
    <div class="container">
      <div class="footer__row">
        <div class="footer__brand">
          <NuxtLink :to="localePath('/')" class="footer__logo">
            <img src="/images/logo.svg" alt="Perfectum 5G" />
          </NuxtLink>

          <div class="footer__contacts">
            <div class="footer__contact">
              <p class="footer__contact-title">{{ t('footer.office_title') }}</p>
              <p class="footer__contact-text">{{ t('footer.address') }}</p>
              <a
                v-if="setting('map_url')"
                class="footer__contact-link"
                :href="setting('map_url')"
                target="_blank"
                rel="noopener"
              >{{ t('footer.map_link') }}</a>
            </div>

            <div v-if="phones.length" class="footer__contact">
              <p class="footer__contact-title">{{ t('footer.phones_title') }}</p>
              <ul class="footer__contact-list">
                <li v-for="phone in phones" :key="phone">
                  <a class="footer__contact-phone" :href="telHref(phone)">{{ phone }}</a>
                </li>
              </ul>
              <p v-if="setting('phone_short')" class="footer__contact-text">
                {{ t('footer.phone_short_note') }} —
                <a class="footer__contact-phone" :href="telHref(setting('phone_short'))">{{ setting('phone_short') }}</a>
              </p>
            </div>

            <div v-if="telegram" class="footer__contact">
              <p class="footer__contact-title">{{ t('footer.telegram_title') }}</p>
              <a
                class="footer__contact-link"
                :href="setting('telegram_url')"
                target="_blank"
                rel="noopener"
              >{{ telegram }}</a>
            </div>

            <div class="footer__contact footer__contact_email">
              <p v-if="emails.length" class="footer__contact-title">{{ t('footer.email_title') }}</p>
              <ul v-if="emails.length" class="footer__contact-list">
                <li v-for="email in emails" :key="email.address" class="footer__contact-text">
                  {{ email.note }} —
                  <a class="footer__contact-phone" :href="`mailto:${email.address}`">{{ email.address }}</a>
                </li>
              </ul>
              <p class="footer__contact-text">{{ t('footer.copyright') }}</p>
            </div>
          </div>
        </div>

        <div class="footer__nav">
          <nav
            v-for="column in menu"
            :key="column.id"
            class="footer__menu"
            :aria-label="column.name ?? t('footer.nav_label')"
          >
            <p class="footer__heading">{{ column.name }}</p>
            <ul class="footer__list">
              <li v-for="item in column.children" :key="item.id" class="footer__list-item">
                <LayoutMenuLink :item="item" link-class="footer__list-link" />
              </li>
            </ul>
          </nav>

          <div class="footer__widgets">
            <div v-if="stores.length" class="footer__widget">
              <p class="footer__heading">{{ t('footer.app_title') }}</p>
              <div class="footer__stores">
                <a
                  v-for="store in stores"
                  :key="store.name"
                  class="store"
                  :href="store.url"
                  target="_blank"
                  rel="noopener"
                  :aria-label="store.name"
                >
                  <img :src="`/images/${store.icon}.svg`" :alt="store.name" loading="lazy" />
                </a>
              </div>
            </div>

            <div v-if="socials.length" class="footer__widget">
              <p class="footer__heading">{{ t('footer.socials_title') }}</p>
              <div class="footer__social">
                <a
                  v-for="social in socials"
                  :key="social.url"
                  class="footer__social-link"
                  :href="social.url"
                  target="_blank"
                  rel="noopener"
                  :aria-label="social.name"
                  v-html="social.svg ?? ''"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>
