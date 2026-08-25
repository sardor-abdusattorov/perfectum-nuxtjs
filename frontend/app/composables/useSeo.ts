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
  const preview = usePreview()
  const t = useT()

  const page = computed(() => (input.page ? settings.value?.pages?.[input.page] ?? null : null))

  const entity = computed(() => toValue(input.title) || '')

  /**
   * A page template may carry `{name}` where the record's own title belongs
   * ("{name} — тариф 5G интернет"). With no record to name yet, the template
   * yields to the site-wide default rather than printing the placeholder.
   */
  function fill(template: string): string {
    if (!template.includes('{name}')) {
      return template
    }

    return entity.value ? template.replaceAll('{name}', entity.value) : ''
  }

  const name = computed(() => {
    const template = page.value?.title || ''

    if (template.includes('{name}')) {
      return fill(template)
    }

    return entity.value || template || (input.titleKey ? t(input.titleKey) : '')
  })

  const title = computed(() => {
    if (!name.value) {
      return settings.value?.seo.title ?? BRAND
    }

    return name.value.includes(BRAND) ? name.value : `${name.value} | ${BRAND}`
  })

  const description = computed(() => fill(toValue(input.description) || page.value?.description || '') || settings.value?.seo.description || '')
  const keywords = computed(() => fill(toValue(input.keywords) || page.value?.keywords || '') || settings.value?.seo.keywords || '')
  const image = computed(() => toValue(input.ogImage) || page.value?.og_image || settings.value?.seo.og_image || undefined)

  useSeoMeta({
    title,
    description,
    keywords,
    /**
     * A preview link is meant for the person it was sent to. Pasted into a
     * public chat it can be followed by a crawler too, so the previewed page
     * asks not to be indexed whatever it says otherwise.
     */
    robots: () => (preview.active.value ? 'noindex, nofollow' : null)
      || toValue(input.robots)
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
