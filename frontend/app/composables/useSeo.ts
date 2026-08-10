export interface SeoInput {
  title?: string
  description?: string
  keywords?: string
  robots?: string
  ogImage?: string
}

/**
 * Page level SEO tags — the Nuxt counterpart of components/seo.blade.php.
 * Defaults will come from the API (Settings::seo()) once the endpoint exists;
 * for now the page passes what it has.
 */
export function useSeo(input: SeoInput = {}) {
  const url = useRequestURL()
  const { locale } = useI18n()

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
    ogUrl: url.href,
    ogLocale: () => locale.value.replace('-', '_'),
    ogSiteName: 'Perfectum',
    ogImage: input.ogImage,

    twitterCard: input.ogImage ? 'summary_large_image' : 'summary',
    twitterTitle: title,
    twitterDescription: description,
    twitterImage: input.ogImage,
  })

  // hreflang alternates come from useLocaleHead() in the layout; canonical is
  // not part of that set, so it is declared here without the query string
  useHead({
    link: [{ rel: 'canonical', href: url.origin + url.pathname }],
  })
}
