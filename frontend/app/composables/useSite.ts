import type { ApiResponse, MenuItem, Menus, Site, Social } from '~/types/api'

export function useSite() {
  const { locale } = useI18n()
  const { $api } = useNuxtApp()

  return useAsyncData<Site>(
    'site',
    () => $api<ApiResponse<Site>>('/site').then(response => response.data),
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

export function useSocials() {
  const { data } = useSite()

  return computed<Social[]>(() => data.value?.socials ?? [])
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
