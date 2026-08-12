import type { MaybeRefOrGetter } from 'vue'

const BRAND = 'Perfectum'

export interface SeoInput {
  page?: string
  titleKey?: string
  title?: MaybeRefOrGetter<string | undefined>
  description?: MaybeRefOrGetter<string | undefined>
  keywords?: MaybeRefOrGetter<string | undefined>
  robots?: MaybeRefOrGetter<string | undefined>
  ogImage?: MaybeRefOrGetter<string | undefined>
}

export function useSeo(input: SeoInput = {}) {
  const settings = useSiteSettings()
  const t = useT()

  const page = computed(() => (input.page ? settings.value?.pages?.[input.page] ?? null : null))

  const name = computed(() => (
    toValue(input.title)
    || page.value?.title
    || (input.titleKey ? t(input.titleKey) : '')
  ))

  const title = computed(() => {
    if (!name.value) {
      return settings.value?.seo.title ?? BRAND
    }

    return name.value.includes(BRAND) ? name.value : `${name.value} | ${BRAND}`
  })

  const description = computed(() => toValue(input.description) || page.value?.description || settings.value?.seo.description || '')
  const image = computed(() => toValue(input.ogImage) || page.value?.og_image || settings.value?.seo.og_image || undefined)

  useSeoMeta({
    title,
    description,
    keywords: () => toValue(input.keywords) || settings.value?.seo.keywords || '',
    robots: () => toValue(input.robots)
      || (page.value && page.value.indexed === false ? 'noindex, nofollow' : null)
      || settings.value?.seo.robots
      || 'index, follow',

    ogTitle: title,
    ogDescription: description,
    ogType: 'website',
    ogSiteName: BRAND,
    ogImage: image,

    twitterCard: () => (image.value ? 'summary_large_image' : 'summary'),
    twitterTitle: title,
    twitterDescription: description,
    twitterImage: image,
  })
}
