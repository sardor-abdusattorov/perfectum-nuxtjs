import type { ApiResponse } from '~/types/api'
import type { Taxonomy } from '~/composables/useTariffs'

export interface DeviceSpec {
  label: string
  value: string
}

export interface DeviceBrand {
  name: string
  slug: string
  logo: string | null
  color: string | null
}

export interface Device {
  slug: string
  name: string
  brand: DeviceBrand | null
  excerpt: string | null
  content: string | null
  specs: DeviceSpec[]
  image: string | null
  price: number | null
  in_stock: boolean
  category: { id: number, name: string, network: string | null } | null
}

export interface DeviceCatalog {
  categories: Taxonomy[]
  devices: Device[]
}

export interface DeviceTab {
  key: string
  label: string
  count: number
}

export function useDeviceCatalog() {
  const { locale } = useI18n()
  const { $api } = useNuxtApp()

  return useAsyncData<DeviceCatalog>(
    'device-catalog',
    async () => {
      const [categories, devices] = await Promise.all([
        $api<ApiResponse<Taxonomy[]>>('/categories/device-categories'),
        $api<ApiResponse<Device[]>>('/devices', { params: { per_page: 48 } }),
      ])

      return { categories: categories.data, devices: devices.data }
    },
    { watch: [locale], default: () => ({ categories: [], devices: [] }) },
  )
}

export function useDevice(slug: MaybeRefOrGetter<string>) {
  const { locale } = useI18n()
  const { $api } = useNuxtApp()

  return useAsyncData(
    () => `device:${toValue(slug)}`,
    () => $api<ApiResponse<Device>>(`/devices/${toValue(slug)}`).then(r => r.data),
    { watch: [locale] },
  )
}

/**
 * The chip row lists every category holding at least one device, plus the
 * shop-window tab for whatever is on sale today.
 */
export function useDeviceTabs(catalog: Ref<DeviceCatalog | null>) {
  const t = useT()

  return computed<DeviceTab[]>(() => {
    const devices = catalog.value?.devices ?? []

    const tabs = (catalog.value?.categories ?? [])
      .map(category => ({
        key: String(category.id),
        label: category.name,
        count: devices.filter(device => device.category?.id === category.id).length,
      }))
      .filter(tab => tab.count > 0)

    const stock = devices.filter(device => device.in_stock).length

    if (stock) {
      tabs.push({ key: 'stock', label: t('devices.in_stock'), count: stock })
    }

    return tabs
  })
}
