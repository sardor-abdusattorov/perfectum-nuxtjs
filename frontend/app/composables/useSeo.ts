export interface SeoInput {
  title?: string
  description?: string
  keywords?: string
  robots?: string
  ogImage?: string
}

export function useSeo(input: SeoInput = {}) {
  const title = input.title ?? 'Perfectum'
  const description = input.description ?? ''

  useSeoMeta({
    title,
    description,
    keywords: input.keywords,
    robots: input.robots ?? 'index, follow',

    ogTitle: title,
    ogDescription: description,
    ogType: 'website',
    ogSiteName: 'Perfectum',
    ogImage: input.ogImage,

    twitterCard: input.ogImage ? 'summary_large_image' : 'summary',
    twitterTitle: title,
    twitterDescription: description,
    twitterImage: input.ogImage,
  })
}
