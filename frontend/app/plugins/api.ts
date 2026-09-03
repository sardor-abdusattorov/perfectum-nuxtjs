export default defineNuxtPlugin(nuxtApp => {
  const config = useRuntimeConfig()
  const preview = useCookie('preview')

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
