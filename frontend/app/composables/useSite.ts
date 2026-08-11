import type { ApiResponse, MenuItem, Menus, Settings, Translations } from '~/types/api'

interface Site {
  settings: Settings
  menus: Menus
  translations: Translations
}

export function useSite() {
  const { locale } = useI18n()
  const { $api } = useNuxtApp()

  return useAsyncData<Site>(
    'site',
    async () => {
      const [settings, menus, translations] = await Promise.all([
        $api<ApiResponse<Settings>>('/settings'),
        $api<ApiResponse<Menus>>('/menus'),
        $api<ApiResponse<Translations>>('/translations'),
      ])

      return {
        settings: settings.data,
        menus: menus.data,
        translations: translations.data,
      }
    },
    { watch: [locale] },
  )
}

export function useSiteSettings() {
  const { data } = useSite()

  return computed(() => data.value?.settings ?? null)
}

export function useMenu(location: keyof Menus) {
  const { data } = useSite()

  return computed<MenuItem[]>(() => data.value?.menus[location] ?? [])
}

export function useSetting() {
  const { data } = useSite()

  return (name: string, fallback = ''): string =>
    data.value?.settings.site[name] ?? fallback
}

export function useT() {
  const { data } = useSite()

  return (path: string, fallback?: string): string => {
    const [category, key] = path.split('.')

    if (!category || !key) {
      return fallback ?? path
    }

    return data.value?.translations[category]?.[key] ?? fallback ?? key
  }
}
