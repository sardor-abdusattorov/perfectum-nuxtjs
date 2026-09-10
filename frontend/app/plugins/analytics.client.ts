/**
 * Nothing reloads when a visitor moves around the site, so the counter, which
 * fires once as the page is parsed, sees only the page they arrived on. A run
 * through the news and the tariffs was reaching Metrika as a single view.
 */
export default defineNuxtPlugin((nuxtApp) => {
  const router = useRouter()
  const settings = useSiteSettings()
  const preview = usePreview()

  let counted = false
  let last = ''
  let referer = ''
  let warned = false

  function counters(): number[] {
    const known = settings.value?.metrics?.yandex_ids ?? []

    if (known.length) {
      return known
    }

    const global = window as unknown as {
      Ya?: { _metrika?: { counters?: Record<string, unknown> } }
      ym?: { a?: unknown[][] }
    }

    const live = global.Ya?._metrika?.counters

    if (live) {
      return Object.keys(live).map(Number).filter(Boolean)
    }

    return (global.ym?.a ?? [])
      .filter(call => call[1] === 'init')
      .map(call => Number(call[0]))
      .filter(Boolean)
  }

  function send(fullPath: string): void {
    const path = fullPath.split('#')[0]!

    if (!counted) {
      counted = true
      last = path
      referer = location.href

      return
    }

    if (path === last || preview.token.value) {
      return
    }

    last = path

    const from = referer
    const url = location.origin + path
    referer = url

    nextTick(() => setTimeout(() => {
      try {
        const ym = (window as unknown as { ym?: (...args: unknown[]) => void }).ym

        if (typeof ym !== 'function') {
          return
        }

        const ids = counters()

        if (!ids.length) {
          if (!warned) {
            warned = true
            console.warn('[metrics] номер счётчика не определён, переходы между страницами не считаются')
          }

          return
        }

        for (const id of ids) {
          ym(id, 'hit', url, { title: document.title, referer: from })
        }
      }
      catch (error) {
        console.warn('[metrics] просмотр не отправлен:', error)
      }
    }, 0))
  }

  nuxtApp.hook('page:finish', () => {
    send(router.currentRoute.value.fullPath)
  })

  /**
   * Listings and filters change only the query, and `page:finish` stays quiet
   * for those — a reader paging through the news would go uncounted again.
   */
  router.afterEach((to, from) => {
    if (to.path === from.path && to.fullPath !== from.fullPath) {
      send(to.fullPath)
    }
  })
})
