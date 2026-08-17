import type { ApiResponse } from '~/types/api'
import type { Taxonomy } from '~/composables/useTariffs'

export interface ServiceFact {
  label: string
  value: string
}

export interface ServiceStep {
  text: string
  code: string | null
}

export interface Service {
  slug: string
  name: string
  excerpt: string | null
  lead: string | null
  content: string | null
  price: string | null
  icon: string | null
  image: string | null
  ussd: string | null
  facts: ServiceFact[]
  steps: ServiceStep[]
  is_featured: boolean
  network: string | null
  category: { id: number, name: string, network: string | null } | null
}

export interface ServiceCatalog {
  categories: Taxonomy[]
  services: Service[]
}

export function useServiceCatalog(network: string) {
  const { locale } = useI18n()
  const { $api } = useNuxtApp()

  return useAsyncData<ServiceCatalog>(
    `service-catalog:${network}`,
    async () => {
      const [categories, services] = await Promise.all([
        $api<ApiResponse<Taxonomy[]>>('/categories/service-categories', { params: { network } }),
        $api<ApiResponse<Service[]>>('/services', { params: { network, per_page: 100 } }),
      ])

      return { categories: categories.data, services: services.data }
    },
    { watch: [locale], default: () => ({ categories: [], services: [] }) },
  )
}

export function useService(slug: MaybeRefOrGetter<string>) {
  const { locale } = useI18n()
  const { $api } = useNuxtApp()

  return useAsyncData(
    () => `service:${toValue(slug)}`,
    () => $api<ApiResponse<Service>>(`/services/${toValue(slug)}`).then(r => r.data),
    { watch: [locale] },
  )
}
