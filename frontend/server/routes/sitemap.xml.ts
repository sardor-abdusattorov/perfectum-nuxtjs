const LOCALES = ['ru', 'uz'] as const

const PAGES = [
  '', '/tariffs', '/tariffs/archive', '/services', '/devices', '/news', '/actions',
  '/faq', '/contacts', '/about-company', '/documents', '/offices', '/coverage-area',
  '/careers', '/procurement', '/numbers', '/help', '/help/contact', '/help/numbers',
  '/cdma', '/cdma/connect', '/cdma/dealers', '/cdma/news',
]

/**
 * Разделы, живущие в обеих сетях, спрашиваются дважды: без параметра
 * `network` бэкенд не фильтрует ничего, и CDMA-записи объявлялись поисковику
 * по адресам 5G — `/ru/news/{slug}` вместо `/ru/cdma/news/{slug}`, а
 * настоящих адресов в карте не было вовсе.
 */
const COLLECTIONS = [
  ['/news', 'news', '5g'],
  ['/cdma/news', 'news', 'cdma'],
  ['/actions', 'actions', '5g'],
  ['/cdma/actions', 'actions', 'cdma'],
  ['/services', 'services', '5g'],
  ['/cdma/services', 'services', 'cdma'],
  ['/tariffs', 'tariffs', '5g'],
  ['/cdma/tariffs', 'tariffs', 'cdma'],
  ['/devices', 'devices', ''],
  ['/careers', 'vacancies', ''],
  ['/procurement', 'tenders', ''],
] as const

const TTL = 3_600_000

type Row = { slug: string, updated_at?: string, published_at?: string }

let builtAt = 0
let cached = ''

async function slugs(base: string, endpoint: string, network: string): Promise<Row[]> {
  const rows: Row[] = []

  for (let page = 1; page <= 20; page++) {
    const response = await $fetch<{ data: Row[], meta?: { last_page: number } }>(
      `${base}/${endpoint}`,
      { query: { per_page: 100, page, ...(network ? { network } : {}) }, timeout: 8000 },
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

  let complete = true

  for (const [route, endpoint, network] of COLLECTIONS) {
    try {
      for (const row of await slugs(api, endpoint, network)) {
        entries.push(url(site, `${route}/${row.slug}`, row.updated_at ?? row.published_at))
      }
    }
    catch (error) {
      complete = false

      console.warn(`[sitemap] раздел ${endpoint} не отдался:`, error)
    }
  }

  const xml = '<?xml version="1.0" encoding="UTF-8"?>'
    + '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"'
    + ' xmlns:xhtml="http://www.w3.org/1999/xhtml">'
    + entries.join('')
    + '</urlset>'

  // Обрезанную карту не кешируем на час: пусть следующий запрос попробует
  // снова, чем поисковик час видит десяток адресов вместо сотен.
  if (complete) {
    cached = xml
    builtAt = Date.now()
  }

  return xml
})
