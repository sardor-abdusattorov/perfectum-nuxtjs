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
    },
    onRequestError({ request, error }) {
      console.error(`[api] ${import.meta.server ? 'SSR' : 'браузер'} не достучался до ${request}: ${error.message}`)
    },
    onResponseError({ request, response }) {
      console.error(`[api] ${request} ответил ${response.status}`)
    },
  })

  return { provide: { api } }
})
