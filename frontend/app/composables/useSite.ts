import type { ApiResponse, MenuItem, MenuLocation, Site, Social } from '~/types/api'

const KEY = 'site'

export function useSite() {
  const { locale } = useI18n()
  const { $api } = useNuxtApp()

  return useAsyncData<Site>(
    KEY,
    () => $api<ApiResponse<Site>>('/site').then(response => response.data),
    { watch: [locale] },
  )
}

function site() {
  return useNuxtData<Site>(KEY).data
}

export function useSiteSettings() {
  const data = site()

  return computed(() => data.value?.settings ?? null)
}

export function useMenu(location: MenuLocation) {
  const data = site()

  return computed<MenuItem[]>(() => data.value?.menus?.[location] ?? [])
}

export function useSocials() {
  const data = site()

  return computed<Social[]>(() => data.value?.socials ?? [])
}

export function useSetting() {
  const data = site()
  const network = useNetwork()

  return (name: string, fallback = ''): string => {
    const settings = data.value?.settings?.site

    if (!settings) {
      return fallback
    }

    const own = network.value === 'cdma' ? settings[`cdma_${name}`] : null

    return own || settings[name] || fallback
  }
}

export function useT() {
  const data = site()

  return (key: string, fallback?: string): string => data.value?.translations[key] ?? fallback ?? key
}
