export default defineNuxtPlugin(nuxtApp => {
  const config = useRuntimeConfig()
  const preview = useCookie('preview')

  const baseURL = (import.meta.server && config.apiBase) || config.public.apiBase

  /**
   * Rendering happens on the server, so without this every page the API sees
   * arrives from the one frontend host: it would count the whole audience as a
   * single reader and throttle them as one. The browser's own calls go through
   * the proxy under server/api, which already forwards the address.
   */
  const visitor = import.meta.server
    ? useRequestHeaders(['x-forwarded-for', 'x-real-ip'])
    : {}

  const forwarded = visitor['x-real-ip'] || visitor['x-forwarded-for'] || ''

  const api = $fetch.create({
    baseURL,
    retry: 1,
    timeout: import.meta.server ? 20000 : 8000,
    headers: { Accept: 'application/json' },
    onRequest({ options }) {
      const locale = nuxtApp.$i18n?.locale
      const value = unref(locale)

      if (value) {
        options.headers.set('X-Locale', value)
      }

      if (forwarded) {
        options.headers.set('x-forwarded-for', forwarded)
      }

      if (preview.value) {
        options.query = { ...options.query, preview: preview.value }
      }
    },
    onRequestError({ request, error }) {
      console.warn(`[api] ${import.meta.server ? 'SSR' : 'браузер'} не достучался до ${request}: ${error.message}`)
    },
    onResponseError({ request, response }) {
      const reason = (response._data as { message?: string } | null)?.message

      console.warn(`[api] ${request} ответил ${response.status}${reason ? `: ${reason}` : ''}`)
    },
  })

  return { provide: { api } }
})
