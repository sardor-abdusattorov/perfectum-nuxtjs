export default defineNuxtPlugin(nuxtApp => {
  const config = useRuntimeConfig()

  const api = $fetch.create({
    baseURL: import.meta.server ? config.apiBase : config.public.apiBase,
    retry: 1,
    timeout: 5000,
    headers: { Accept: 'application/json' },
    onRequest({ options }) {
      const locale = nuxtApp.$i18n?.locale
      const value = unref(locale)

      if (value) {
        options.headers.set('X-Locale', value)
      }
    },
  })

  return { provide: { api } }
})
