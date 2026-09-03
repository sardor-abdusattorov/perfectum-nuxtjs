const OWN_ROUTES = new Set([
  'about-company', 'actions', 'app', 'careers', 'cdma', 'contacts', 'coverage-area',
  'devices', 'documents', 'faq', 'help', 'news', 'numbers', 'offices',
  'pages', 'procurement', 'services', 'tariffs',
])

const TTL = 60_000

let fetchedAt = 0
let map: Record<string, string> = {}

export default defineEventHandler(async (event) => {
  if (event.method !== 'GET') {
    return
  }

  const [path = '', query = ''] = event.path.split('?')

  if (path.includes('.') || path.startsWith('/_') || path.startsWith('/api')) {
    return
  }

  const trimmed = path.replace(/^\/+|\/+$/g, '')
  const locale = /^(ru|uz)(\/|$)/.exec(trimmed)?.[1]
  const bare = locale ? trimmed.slice(locale.length + 1) : trimmed

  if (!bare || OWN_ROUTES.has(bare.split('/')[0]!)) {
    return
  }

  if (Date.now() - fetchedAt > TTL) {
    fetchedAt = Date.now()

    const config = useRuntimeConfig(event)
    const base = (config.apiBase as string) || config.public.apiBase

    try {
      const response = await $fetch<{ data: Record<string, string> }>(`${base}/redirects`, { timeout: 4000 })

      map = response.data ?? {}
    }
    catch {
    }
  }

  const target = map[bare]

  if (target) {
    return sendRedirect(event, `/${locale ?? 'ru'}${target}${query ? `?${query}` : ''}`, 301)
  }
})
