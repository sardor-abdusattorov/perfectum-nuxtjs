export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

  modules: ['@nuxtjs/i18n'],

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

  css: [
    'swiper/css',
    'swiper/css/a11y',
    'swiper/css/autoplay',
    'swiper/css/free-mode',
    'swiper/css/navigation',
    'swiper/css/pagination',
    'swiper/css/scrollbar',
    '~/assets/css/settings.css',
    '~/assets/css/main.css',
  ],


  i18n: {
    defaultLocale: 'ru',
    strategy: 'prefix',
    baseUrl: process.env.NUXT_PUBLIC_SITE_URL || 'http://localhost:3000',
    locales: [
      { code: 'ru', language: 'ru-RU', name: 'RU' },
      { code: 'uz', language: 'uz-UZ', name: 'UZ' },
    ],
    detectBrowserLanguage: {
      useCookie: true,
      cookieKey: 'i18n_locale',
      redirectOn: 'root',
    },
  },

  nitro: {
    /**
     * Nothing in front of Node is guaranteed to compress, and a stylesheet or a
     * traced logo is mostly repeated text: the build writes .gz and .br beside
     * every public asset and the server hands those over when it can.
     */
    compressPublicAssets: { gzip: true, brotli: true },

    routeRules: {
      '/_nuxt/**': { headers: { 'cache-control': 'public, max-age=31536000, immutable' } },
      '/images/**': { headers: { 'cache-control': 'public, max-age=2592000' } },
      '/fonts/**': { headers: { 'cache-control': 'public, max-age=31536000, immutable' } },
    },
  },

  runtimeConfig: {
    apiBase: '',
    public: {
      apiBase: '/api/v1',
      siteUrl: 'http://localhost:3000',
      yandexMapsKey: '',
    },
  },

  /**
   * In development the API is the Laravel server next door, so `npm run dev`
   * works before any .env exists. A build still refuses to guess the address.
   */
  $development: {
    runtimeConfig: {
      apiBase: 'http://localhost:8000/api/v1',
    },
  },

  typescript: {
    nodeTsConfig: {
      compilerOptions: {
        types: ['node'],
      },
    },
  },

  vite: {
    server: {
      watch: {
        usePolling: process.env.VITE_USE_POLLING === 'true',
      },
    },
  },
})
