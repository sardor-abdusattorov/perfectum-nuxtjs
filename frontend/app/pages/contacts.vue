<script setup lang="ts">
await useBlocks('contacts')

const hero = useBlock('contacts', 'page_hero')
const cards = useBlock('contacts', 'cards')

const t = useT()
const setting = useSetting()
const socials = useSocials()

useSeo({ page: 'contacts', titleKey: 'seo.contacts' })

const items = computed(() => published(cards.value.items))

function phones(): Array<{ label: string, href: string }> {
  return [setting('phone_primary'), setting('phone_secondary')]
    .filter(Boolean)
    .map(phone => ({ label: phone, href: `tel:${phone.replace(/\s/g, '')}` }))
}

function emails(): Array<{ label: string, href: string }> {
  return [setting('email_info'), setting('email_hotline')]
    .filter(Boolean)
    .map(email => ({ label: email, href: `mailto:${email}` }))
}
</script>

<template>
  <PageHero
    variant="page-hero_inner page-hero_company page-hero_contacts"
    :crumb="t('seo.contacts')"
    :eyebrow="hero.eyebrow"
    eyebrow-silver
    :title="rich(hero.title, { accent: 'page-hero__title-red' })"
    :subtitle="hero.subtitle"
  />

  <section class="company">
    <div class="container">
      <CompanyNav active="contacts" />

      <ul v-if="items.length" class="contacts-grid">
        <li v-for="(card, index) in items" :key="index" class="contact-card">
          <ContactCardIcon :svg="card.icon_svg" />
          <h2 class="contact-card__title">{{ card.title }}</h2>

          <p v-if="card.type === 'office'" class="contact-card__text">{{ card.text || t('footer.address') }}</p>

          <p v-else-if="card.type === 'phones'" class="contact-card__text">
            <template v-for="(phone, i) in phones()" :key="phone.href">
              <br v-if="i" />
              <a :href="phone.href">{{ phone.label }}</a>
            </template>
          </p>

          <p v-else-if="card.type === 'emails'" class="contact-card__text">
            <template v-for="(email, i) in emails()" :key="email.href">
              <br v-if="i" />
              <a :href="email.href">{{ email.label }}</a>
            </template>
          </p>

          <p v-if="card.note" class="contact-card__text">{{ card.note }}</p>

          <div v-if="card.type === 'socials' && socials.length" class="contact-card__socials">
            <a
              v-for="social in socials"
              :key="social.url"
              class="contact-card__social"
              :href="social.url"
              target="_blank"
              rel="noopener"
            >
              <span class="contact-card__social-icon" aria-hidden="true" v-html="social.svg ?? ''"></span>
              {{ social.name }}
            </a>
          </div>
        </li>
      </ul>

      <div v-if="cards.note" class="contacts-note rich" v-html="cards.note"></div>
    </div>
  </section>
</template>
