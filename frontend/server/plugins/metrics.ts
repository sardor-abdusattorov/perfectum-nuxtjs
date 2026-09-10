import type { H3Event } from 'h3'

const TTL = 60_000

const PRECONNECT = '<link rel="preconnect" href="https://mc.yandex.ru" crossorigin>'
  + '<link rel="preconnect" href="https://www.googletagmanager.com" crossorigin>'

interface Snippet {
  head: string
  body: string
}

let cache: Snippet | null = null
let fetchedAt = 0
let refreshing = false

/**
 * The counter code is whatever the panel was given — Yandex hands out a block
 * with a <noscript> pixel in it, and the tag manager wants its <iframe> at the
 * top of the body. Splitting on <noscript> puts each half where it belongs
 * without pretending to understand the rest.
 */
function split(html: string): Snippet {
  const body: string[] = []

  const head = html.replace(/<noscript[\s\S]*?<\/noscript>/gi, (match) => {
    body.push(match)

    return ''
  })

  return { head, body: body.join('') }
}

async function load(event: H3Event): Promise<void> {
  const base = String(useRuntimeConfig(event).apiBase || '').replace(/\/+$/, '')

  if (!base) {
    return
  }

  try {
    const response = await $fetch<{ data: Record<string, string> }>(`${base}/metrics`, { timeout: 3000 })

    cache = split(Object.values(response.data ?? {}).join('\n'))
    fetchedAt = Date.now()
  }
  catch (error) {
    console.warn('[metrics] не удалось получить код счётчиков:', error)

    fetchedAt = Date.now() - TTL + 10_000
  }
}

/**
 * The counters ride in the server-rendered HTML, the way they did on the old
 * site: every visitor is counted, at once, whether or not they ever touch the
 * cookie notice and whether or not the page's own JavaScript comes up.
 */
export default defineNitroPlugin((nitro) => {
  nitro.hooks.hook('render:html', async (html, { event }) => {
    if (getCookie(event, 'preview') || getQuery(event).preview) {
      return
    }

    if (!cache) {
      await load(event)
    }
    else if (Date.now() - fetchedAt > TTL && !refreshing) {
      refreshing = true

      load(event).finally(() => {
        refreshing = false
      })
    }

    if (!cache?.head) {
      return
    }

    html.head.push(PRECONNECT + cache.head)

    if (cache.body) {
      html.bodyPrepend.unshift(cache.body)
    }
  })
})
