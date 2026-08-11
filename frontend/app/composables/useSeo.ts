export interface SeoInput {
  title?: string
  description?: string
  keywords?: string
  robots?: string
  ogImage?: string
}

export function useSeo(input: SeoInput = {}) {
  const settings = useSiteSettings()

  const title = computed(() => input.title ?? settings.value?.seo.title ?? 'Perfectum')
  const description = computed(() => input.description ?? settings.value?.seo.description ?? '')
  const image = computed(() => input.ogImage ?? settings.value?.seo.og_image ?? undefined)

  useSeoMeta({
    title,
    description,
    keywords: () => input.keywords ?? settings.value?.seo.keywords ?? '',
    robots: () => input.robots ?? settings.value?.seo.robots ?? 'index, follow',

    ogTitle: title,
    ogDescription: description,
    ogType: 'website',
    ogSiteName: 'Perfectum',
    ogImage: image,

    twitterCard: () => (image.value ? 'summary_large_image' : 'summary'),
    twitterTitle: title,
    twitterDescription: description,
    twitterImage: image,
  })
}
