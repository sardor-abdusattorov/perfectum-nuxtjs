// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

  modules: ['@nuxtjs/i18n'],

  css: [
    'swiper/css',
    'swiper/css/navigation',
    'swiper/css/scrollbar',
    '~/assets/css/settings.css',
    '~/assets/css/main.css',
  ],

  i18n: {
    defaultLocale: 'ru',
    strategy: 'prefix',
    locales: [
      { code: 'ru', language: 'ru-RU', name: 'RU', file: 'ru.json' },
      { code: 'uz', language: 'uz-UZ', name: 'UZ', file: 'uz.json' },
      { code: 'en', language: 'en-US', name: 'EN', file: 'en.json' },
    ],
    detectBrowserLanguage: {
      useCookie: true,
      cookieKey: 'i18n_locale',
      redirectOn: 'root',
    },
  },

  runtimeConfig: {
    // NUXT_API_BASE — server side only, resolved inside the Docker network
    apiBase: 'http://localhost:8000/api/v1',
    public: {
      // NUXT_PUBLIC_API_BASE — shipped to the browser
      apiBase: 'http://localhost:8000/api/v1',
    },
  },

  vite: {
    server: {
      watch: {
        // Bind mounts do not deliver inotify events on macOS and Windows
        usePolling: process.env.VITE_USE_POLLING === 'true',
      },
    },
  },
})
