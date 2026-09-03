import type { ApiResponse } from '~/types/api'
import type { Taxonomy } from '~/composables/useTariffs'

export type OfficeType = 'office' | 'dealer'

export interface Office {
  id: number
  type: OfficeType
  name: string | null
  district: string | null
  address: string
  phone: string | null
  lat: number | null
  lng: number | null
  dealers_count: number | null
  content: string | null
  region: Taxonomy | null
}

export interface OfficeQuery {
  network?: MaybeRefOrGetter<string | undefined>
  type?: MaybeRefOrGetter<OfficeType | undefined>
}

export function useOffices(query: OfficeQuery = {}) {
  const { locale } = useI18n()
  const { $api } = useNuxtApp()

  const params = computed(() => ({
    network: toValue(query.network),
    type: toValue(query.type),
  }))

  return useAsyncData(
    () => `offices:${params.value.network ?? 'all'}:${params.value.type ?? 'all'}`,
    async () => {
      const [regions, offices] = await Promise.all([
        $api<ApiResponse<Taxonomy[]>>('/categories/regions', { params: { network: params.value.network } }),
        $api<ApiResponse<Office[]>>('/offices', { params: params.value }),
      ])

      return { regions: regions.data, offices: offices.data }
    },
    { watch: [locale, params], default: () => ({ regions: [], offices: [] }) },
  )
}

export function officeTitle(office: Office): string {
  return office.name || office.district || office.region?.name || office.address
}

export function officeMatches(office: Office, search: string): boolean {
  if (!search) {
    return true
  }

  const query = search.toLowerCase()

  return [officeTitle(office), office.address, office.district, office.region?.name]
    .some(value => value?.toLowerCase().includes(query))
}
