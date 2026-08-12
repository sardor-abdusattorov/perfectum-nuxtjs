import type { ApiResponse, TariffButton } from '~/types/api'

export interface Taxonomy {
  slug: string
  name: string
  network: string | null
}

export interface TariffFeature {
  icon: string | null
  title: string
  note: string
}

export interface TariffDescription {
  name: string
  content: string
}

export interface Tariff {
  slug: string
  name: string
  price: string
  price_currency: string
  price_period: string
  features: TariffFeature[]
  descriptions: TariffDescription[]
  image: string | null
  modal_image: string | null
  buttons: TariffButton[]
  category: Taxonomy | null
  type: Taxonomy | null
}

export interface TariffCatalog {
  categories: Taxonomy[]
  types: Taxonomy[]
  tariffs: Tariff[]
}

export function useTariffCatalog() {
  const { locale } = useI18n()
  const { $api } = useNuxtApp()

  return useAsyncData<TariffCatalog>(
    'tariff-catalog',
    async () => {
      const [categories, types, tariffs] = await Promise.all([
        $api<ApiResponse<Taxonomy[]>>('/categories/tariff-categories'),
        $api<ApiResponse<Taxonomy[]>>('/categories/tariff-types'),
        $api<ApiResponse<Tariff[]>>('/tariffs', { params: { per_page: 100 } }),
      ])

      return { categories: categories.data, types: types.data, tariffs: tariffs.data }
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

/**
 * Category, subcategory, tariff — the three levels the catalogue is built on.
 * The chips list only the subcategories present in the open category, and
 * switching category resets them, the way the old site behaved.
 */
export function useTariffFilter(catalog: Ref<TariffCatalog | null>) {
  const categories = computed(() => catalog.value?.categories ?? [])
  const tariffs = computed(() => catalog.value?.tariffs ?? [])

  const category = ref('')
  const type = ref('')

  watchEffect(() => {
    if (!category.value && categories.value.length) {
      category.value = categories.value[0]!.slug
    }
  })

  const inCategory = computed(() => tariffs.value.filter(item => item.category?.slug === category.value))

  const types = computed(() => {
    const present = new Set(inCategory.value.map(item => item.type?.slug).filter(Boolean))

    return (catalog.value?.types ?? []).filter(item => present.has(item.slug))
  })

  const visible = computed(() => (
    type.value ? inCategory.value.filter(item => item.type?.slug === type.value) : inCategory.value
  ))

  watch(category, () => {
    type.value = ''
  })

  return { categories, types, category, type, visible }
}
