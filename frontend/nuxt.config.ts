// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

  modules: ['@nuxtjs/i18n'],

  // components/head.blade.php — everything static that used to sit in <head>
  app: {
    head: {
      charset: 'utf-8',
      viewport: 'width=device-width, initial-scale=1',
      meta: [
        { name: 'theme-color', content: '#ffffff' },
      ],
      link: [
        { rel: 'apple-touch-icon', sizes: '180x180', href: '/images/favicon/apple-touch-icon.png' },
        { rel: 'icon', type: 'image/png', sizes: '32x32', href: '/images/favicon/favicon-32x32.png' },
        { rel: 'icon', type: 'image/png', sizes: '16x16', href: '/images/favicon/favicon-16x16.png' },
        { rel: 'manifest', href: '/images/favicon/site.webmanifest' },
      ],
    },
  },

  // @vite([...]) — Vite picks these up and injects the built tags itself
  css: [
    'swiper/css/bundle',
    '~/assets/css/settings.css',
    '~/assets/css/main.css',
  ],

  i18n: {
    defaultLocale: 'ru',
    strategy: 'prefix',
    baseUrl: process.env.NUXT_PUBLIC_SITE_URL || 'http://localhost:3000',
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
      siteUrl: 'http://localhost:3000',
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
