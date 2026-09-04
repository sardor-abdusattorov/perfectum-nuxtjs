import type { ApiResponse } from '~/types/api'
import type { Taxonomy } from '~/composables/useTariffs'

export interface FaqItem {
  question: string
  answer: string
  category: Taxonomy | null
}

export type FaqPage = 'faq' | 'help' | 'cdma'

export interface FaqQuery {
  page: FaqPage
  withCategories?: boolean
}

export function useFaqs(query: FaqQuery) {
  const { locale } = useI18n()
  const { $api } = useNuxtApp()
  const network = useNetwork()

  return useAsyncData(
    `faqs:${query.page}`,
    async () => {
      const [faqs, categories] = await Promise.all([
        $api<ApiResponse<FaqItem[]>>('/faqs', { params: { page: query.page, network: network.value } }),
        query.withCategories
          ? $api<ApiResponse<Taxonomy[]>>('/categories/faq-categories', { params: { network: network.value } })
          : Promise.resolve({ data: [] as Taxonomy[] }),
      ])

      return { faqs: faqs.data, categories: categories.data }
    },
    { watch: [locale], default: () => ({ faqs: [], categories: [] }) },
  )
}
