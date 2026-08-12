import type { ApiResponse } from '~/types/api'
import type { Taxonomy } from '~/composables/useTariffs'

export interface FaqItem {
  question: string
  answer: string
  category: Taxonomy | null
}

export interface FaqQuery {
  network?: MaybeRefOrGetter<string | undefined>
  featured?: boolean
  withCategories?: boolean
}

export function useFaqs(query: FaqQuery = {}) {
  const { locale } = useI18n()
  const { $api } = useNuxtApp()

  const network = computed(() => toValue(query.network))

  return useAsyncData(
    () => `faqs:${network.value ?? 'all'}:${query.featured ? 'featured' : 'all'}`,
    async () => {
      const params = { network: network.value, featured: query.featured ? 1 : undefined }

      const [faqs, categories] = await Promise.all([
        $api<ApiResponse<FaqItem[]>>('/faqs', { params }),
        query.withCategories
          ? $api<ApiResponse<Taxonomy[]>>('/categories/faq-categories', { params: { network: network.value } })
          : Promise.resolve({ data: [] as Taxonomy[] }),
      ])

      return { faqs: faqs.data, categories: categories.data }
    },
    { watch: [locale, network], default: () => ({ faqs: [], categories: [] }) },
  )
}
