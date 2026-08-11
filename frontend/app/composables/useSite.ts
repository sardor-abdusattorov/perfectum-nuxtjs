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

export function useMenu(location: 'header' | 'footer') {
  const { data } = useSite()
  const network = useNetwork()

  return computed<MenuItem[]>(() => {
    const menus = data.value?.menus

    if (!menus) {
      return []
    }

    const own = network.value === 'cdma' ? menus[`cdma_${location}`] : undefined

    return own?.length ? own : menus[location] ?? []
  })
}

export function useSocials() {
  const { data } = useSite()

  return computed<Social[]>(() => data.value?.socials ?? [])
}

export function useSetting() {
  const { data } = useSite()
  const network = useNetwork()

  return (name: string, fallback = ''): string => {
    const site = data.value?.settings.site

    if (!site) {
      return fallback
    }

    const own = network.value === 'cdma' ? site[`cdma_${name}`] : null

    return own ?? site[name] ?? fallback
  }
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
