export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

  modules: ['@nuxt/icon', '@nuxtjs/i18n'],

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
    'swiper/css/bundle',
    '~/assets/css/settings.css',
    '~/assets/css/main.css',
  ],

  icon: {
    mode: 'svg',
    clientBundle: {
      scan: true,
      icons: [
        'simple-icons:telegram',
        'simple-icons:instagram',
        'simple-icons:facebook',
        'simple-icons:youtube',
        'simple-icons:tiktok',
        'simple-icons:x',
        'simple-icons:linkedin',
        'simple-icons:threads',
        'simple-icons:whatsapp',
        'simple-icons:viber',
        'simple-icons:odnoklassniki',
        'simple-icons:vk',
      ],
      sizeLimitKb: 512,
    },
    serverBundle: { collections: ['simple-icons'] },
  },

  i18n: {
    defaultLocale: 'ru',
    strategy: 'prefix',
    baseUrl: process.env.NUXT_PUBLIC_SITE_URL || 'http://localhost:3000',
    locales: [
      { code: 'ru', language: 'ru-RU', name: 'RU' },
      { code: 'uz', language: 'uz-UZ', name: 'UZ' },
      { code: 'en', language: 'en-US', name: 'EN' },
    ],
    detectBrowserLanguage: {
      useCookie: true,
      cookieKey: 'i18n_locale',
      redirectOn: 'root',
    },
  },

  runtimeConfig: {
    apiBase: 'http://localhost:8000/api/v1',
    public: {
      apiBase: 'http://localhost:8000/api/v1',
      siteUrl: 'http://localhost:3000',
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
