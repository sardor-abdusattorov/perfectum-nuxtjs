import type { ApiResponse, TariffButton } from '~/types/api'

export interface Taxonomy {
  slug: string
  name: string
  network: string | null
}

export interface TariffFeature {
  icon: string | null
  title: string
}

export interface Tariff {
  slug: string
  name: string
  price: string
  price_currency: string
  price_period: string
  lead: string | null
  terms: string | null
  features: TariffFeature[]
  image: string | null
  modal_image: string | null
  ussd: string | null
  buttons: TariffButton[]
  is_featured: boolean
  is_archived: boolean
  category: Taxonomy | null
  type: Taxonomy | null
}

export function useTariffCatalog() {
  const { locale } = useI18n()
  const { $api } = useNuxtApp()

  return useAsyncData(
    'tariff-catalog',
    async () => {
      const [categories, types, tariffs] = await Promise.all([
        $api<ApiResponse<Taxonomy[]>>('/categories/tariff-categories'),
        $api<ApiResponse<Taxonomy[]>>('/categories/tariff-types'),
        $api<ApiResponse<Tariff[]>>('/tariffs', { params: { per_page: 100 } }),
      ])

      return {
        categories: categories.data,
        types: types.data,
        tariffs: tariffs.data,
      }
    },
    { watch: [locale], default: () => ({ categories: [], types: [], tariffs: [] }) },
  )
}

export function useTariff(slug: MaybeRefOrGetter<string>) {
  const { locale } = useI18n()
  const { $api } = useNuxtApp()

  return useAsyncData(
    () => `tariff:${toValue(slug)}`,
    () => $api<ApiResponse<Tariff>>(`/tariffs/${toValue(slug)}`).then(r => r.data),
    { watch: [locale] },
  )
}
