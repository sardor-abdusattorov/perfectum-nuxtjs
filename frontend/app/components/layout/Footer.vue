<script setup lang="ts">
const localePath = useLocalePath()
const menu = useMenu('footer')
const socials = useSocials()
const setting = useSetting()
const t = useT()

const phones = computed(() => [setting('phone_primary'), setting('phone_secondary')].filter(Boolean))
const emails = computed(() => lines(t('footer.emails')))
const telegram = computed(() => setting('telegram_url').replace(/^.*\/(?=[^/]+$)/, '@'))

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
                          <h2 class="footer__contact-title">{{ t('footer.office_title', 'Головной офис') }}</h2>
                          <p class="footer__contact-text">{{ t('footer.address') }}</p>
                          <a
                              v-if="setting('map_url')"
                              class="footer__contact-link"
                              :href="setting('map_url')"
                              target="_blank"
                              rel="noopener"
                          >{{ t('footer.map_link', 'Показать на карте') }}</a>
                      </div>
                      <div class="footer__contact">
                          <h2 class="footer__contact-title">{{ t('footer.phones_title', 'Телефоны поддержки') }}</h2>
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
                          <h2 class="footer__contact-title">{{ t('footer.telegram_title', 'Чат с оператором в Telegram') }}</h2>
                          <a
                              class="footer__contact-link"
                              :href="setting('telegram_url')"
                              target="_blank"
                              rel="noopener"
                          >{{ telegram }}</a>
                      </div>
                      <div class="footer__contact footer__contact_email">
                          <h2 class="footer__contact-title">{{ t('footer.email_title', 'Email') }}</h2>
                          <p class="footer__contact-text">
                              <template v-for="(line, index) in emails" :key="index">
                                  <br v-if="index" />{{ line }}
                              </template>
                          </p>
                          <p class="footer__contact-text">{{ t('footer.copyright') }}</p>
                      </div>
                  </div>
              </div>
              <div class="footer__nav">
                  <div v-for="column in menu" :key="column.id" class="footer__menu">
                      <h2 class="footer__heading">{{ column.name }}</h2>
                      <ul class="footer__list">
                          <li v-for="item in column.children" :key="item.id" class="footer__list-item">
                              <LayoutMenuLink :item="item" link-class="footer__list-link" />
                          </li>
                      </ul>
                  </div>
                  <div class="footer__widgets">
                      <div class="footer__widget">
                          <h2 class="footer__heading">{{ t('footer.app_title', 'Мобильное приложение') }}</h2>
                          <div class="footer__stores">
                              <a class="store store_small" :href="setting('google_play_url', '#')" target="_blank" rel="noopener">
                                  <img src="/images/google_play.svg" alt="Google Play" />
                              </a>
                              <a class="store store_small" :href="setting('app_store_url', '#')" target="_blank" rel="noopener">
                                  <img src="/images/apple.svg" alt="App Store" />
                              </a>
                          </div>
                      </div>
                      <div class="footer__widget">
                          <h2 class="footer__heading">{{ t('footer.socials_title', 'Социальные сети') }}</h2>
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
