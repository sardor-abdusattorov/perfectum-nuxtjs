export default defineNuxtPlugin(nuxtApp => {
  const config = useRuntimeConfig()

  const baseURL = (import.meta.server && config.apiBase) || config.public.apiBase

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

      /**
       * A draft is a 404 for the site, and the token in the address is what
       * lifts that for the one record it names. Every page reaches the API
       * through here, so the token travels from here too — the page itself
       * never has to know it exists.
       */
      const preview = nuxtApp.$router?.currentRoute.value.query.preview

      if (typeof preview === 'string' && preview) {
        options.query = { ...options.query, preview }
      }
    },
    /**
     * Every caller handles its own failure and says so on the page, so these
     * lines are a trail for whoever is looking — a warning, not an error the
     * browser had to swallow.
     */
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
