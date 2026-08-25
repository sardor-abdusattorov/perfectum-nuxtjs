import type { ApiResponse, TariffButton } from '~/types/api'

export interface Taxonomy {
  id: number
  name: string
  slug?: string | null
  network: string | null
  in_catalog?: boolean | null
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
  // a category switched out of the catalogue keeps serving its own section
  // (the CDMA landing), it just loses its tab here and on the homepage
  const categories = computed(() => (
    (catalog.value?.categories ?? []).filter(item => item.in_catalog !== false)
  ))
  const tariffs = computed(() => catalog.value?.tariffs ?? [])

  const category = ref<number | ''>('')
  const type = ref<number | ''>('')

  const inCategory = computed(() => tariffs.value.filter(item => item.category?.id === category.value))

  const types = computed(() => {
    const present = new Set(inCategory.value.map(item => item.type?.id).filter(Boolean))

    return (catalog.value?.types ?? []).filter(item => present.has(item.id))
  })

  const visible = computed(() => (
    type.value ? inCategory.value.filter(item => item.type?.id === type.value) : inCategory.value
  ))

  /**
   * Both switches live in the address as slugs, so the state of the page is a
   * link anyone can send. The old positional ?tab= is still read — advertising
   * may carry it — but what gets written is always the slug.
   */
  const route = useRoute()
  const router = useRouter()

  watchEffect(() => {
    if (!category.value && categories.value.length) {
      const named = categories.value.find(item => item.slug === route.query.category)
      const counted = categories.value[Number(route.query.tab) - 1]

      category.value = (named ?? counted ?? categories.value[0]!).id
    }
  })

  watchEffect(() => {
    if (!type.value && route.query.type) {
      type.value = types.value.find(item => item.slug === route.query.type)?.id ?? ''
    }
  })

  watch([category, type], () => {
    const open = categories.value.find(item => item.id === category.value)?.slug
    const chip = types.value.find(item => item.id === type.value)?.slug

    if (open === route.query.category && (chip ?? undefined) === route.query.type) {
      return
    }

    router.replace({ query: { ...route.query, tab: undefined, category: open, type: chip } })
  })

  // the chips belong to the open category, so changing it drops the chosen one
  watch(category, (next, previous) => {
    if (previous !== '') {
      type.value = ''
    }
  })

  return { categories, types, category, type, visible }
}
