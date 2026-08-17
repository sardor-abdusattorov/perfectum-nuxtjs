/**
 * Nine hundred addresses now answer on this site, and most of them are reached
 * through a filter a crawler cannot press. The map lists every one of them in
 * both languages, each address naming the other as its alternate so the two
 * versions are indexed as one page rather than as duplicates.
 *
 * The lists come from the API in one request apiece and are held for an hour —
 * a news item published in the admin appears in the map on the next refresh.
 */
const LOCALES = ['ru', 'uz'] as const

const PAGES = [
  '', '/tariffs', '/tariffs/archive', '/services', '/devices', '/news', '/actions',
  '/faq', '/contacts', '/about-company', '/documents', '/offices', '/coverage-area',
  '/careers', '/procurement', '/numbers', '/help', '/help/contact', '/help/numbers',
  '/cdma', '/cdma/connect', '/cdma/dealers',
]

const COLLECTIONS = [
  ['/news', 'news'],
  ['/actions', 'actions'],
  ['/services', 'services'],
  ['/tariffs', 'tariffs'],
  ['/devices', 'devices'],
  ['/careers', 'vacancies'],
  ['/procurement', 'tenders'],
] as const

const TTL = 3_600_000

type Row = { slug: string, updated_at?: string, published_at?: string }

let builtAt = 0
let cached = ''

async function slugs(base: string, endpoint: string): Promise<Row[]> {
  const rows: Row[] = []

  for (let page = 1; page <= 20; page++) {
    const response = await $fetch<{ data: Row[], meta?: { last_page: number } }>(
      `${base}/${endpoint}`,
      { query: { per_page: 100, page }, timeout: 8000 },
    )

    rows.push(...(response.data ?? []))

    if (page >= (response.meta?.last_page ?? 1)) {
      break
    }
  }

  return rows
}

function url(base: string, path: string, updated?: string): string {
  const alternates = LOCALES.map(locale => (
    `<xhtml:link rel="alternate" hreflang="${locale}" href="${base}/${locale}${path}" />`
  )).join('')

  return LOCALES.map(locale => [
    '<url>',
    `<loc>${base}/${locale}${path}</loc>`,
    alternates,
    updated ? `<lastmod>${updated.slice(0, 10)}</lastmod>` : '',
    '</url>',
  ].join('')).join('')
}

export default defineEventHandler(async (event) => {
  setHeader(event, 'content-type', 'application/xml; charset=utf-8')

  if (cached && Date.now() - builtAt < TTL) {
    return cached
  }

  const config = useRuntimeConfig(event)
  const api = (config.apiBase as string) || config.public.apiBase
  const site = (config.public.siteUrl as string).replace(/\/+$/, '')

  const entries = [...PAGES.map(path => url(site, path))]

  for (const [route, endpoint] of COLLECTIONS) {
    try {
      for (const row of await slugs(api, endpoint)) {
        entries.push(url(site, `${route}/${row.slug}`, row.updated_at ?? row.published_at))
      }
    }
    catch {
      // the map must outlive the api; the rest of the site is still listed
    }
  }

  cached = '<?xml version="1.0" encoding="UTF-8"?>'
    + '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"'
    + ' xmlns:xhtml="http://www.w3.org/1999/xhtml">'
    + entries.join('')
    + '</urlset>'
  builtAt = Date.now()

  return cached
})
