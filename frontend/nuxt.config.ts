// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

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
