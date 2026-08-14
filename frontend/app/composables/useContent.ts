import type { ApiResponse } from '~/types/api'
import type { Taxonomy } from '~/composables/useTariffs'

export interface Paginated<T> {
  data: T[]
  meta: { current_page: number, last_page: number, total: number }
}

export interface NewsItem {
  slug: string
  title: string
  excerpt: string | null
  content: string
  preview_image: string | null
  main_image: string | null
  is_featured: boolean
  published_at: string | null
  category: Taxonomy | null
}

export interface ActionItem {
  slug: string
  title: string
  badge: string | null
  excerpt: string | null
  content: string
  preview_image: string | null
  main_image: string | null
  starts_at: string | null
  ends_at: string | null
  category: Taxonomy | null
}

export interface VacancyItem {
  slug: string
  title: string
  department: string | null
  city: string | null
  employment: string | null
  salary: string | null
  content: string
}

export interface TenderItem {
  slug: string
  title: string
  content: string
  state: 'open' | 'closed'
  deadline_at: string | null
  files: string[]
}

interface ListQuery {
  network?: string
  category?: MaybeRefOrGetter<number | string | undefined>
  page?: MaybeRefOrGetter<number>
  search?: MaybeRefOrGetter<string | undefined>
  perPage?: number
}

const PER_PAGE = 9

function useList<T>(endpoint: string, query: ListQuery, withCategories?: string) {
  const { locale } = useI18n()
  const { $api } = useNuxtApp()

  const params = computed(() => ({
    network: query.network,
    category: toValue(query.category) || undefined,
    search: toValue(query.search) || undefined,
    page: toValue(query.page) ?? 1,
    per_page: query.perPage ?? PER_PAGE,
  }))

  return useAsyncData(
    () => `${endpoint}:${JSON.stringify(params.value)}`,
    async () => {
      const [list, categories] = await Promise.all([
        $api<Paginated<T>>(`/${endpoint}`, { params: params.value }),
        withCategories
          ? $api<ApiResponse<Taxonomy[]>>(`/categories/${withCategories}`)
          : Promise.resolve({ data: [] as Taxonomy[] }),
      ])

      return { items: list.data, meta: list.meta, categories: categories.data }
    },
    {
      watch: [locale, params],
      default: () => ({ items: [] as T[], meta: { current_page: 1, last_page: 1, total: 0 }, categories: [] as Taxonomy[] }),
    },
  )
}

function useItem<T>(endpoint: string, slug: MaybeRefOrGetter<string>) {
  const { locale } = useI18n()
  const { $api } = useNuxtApp()

  return useAsyncData(
    () => `${endpoint}:${toValue(slug)}`,
    () => $api<ApiResponse<T>>(`/${endpoint}/${toValue(slug)}`).then(r => r.data),
    { watch: [locale] },
  )
}

export const useNewsList = (query: ListQuery, withCategories = false) =>
  useList<NewsItem>('news', query, withCategories ? 'news-categories' : undefined)
export const useNewsItem = (slug: MaybeRefOrGetter<string>) => useItem<NewsItem>('news', slug)

export const useActionsList = (query: ListQuery, withCategories = false) =>
  useList<ActionItem>('actions', query, withCategories ? 'action-categories' : undefined)
export const useActionItem = (slug: MaybeRefOrGetter<string>) => useItem<ActionItem>('actions', slug)

export const useVacanciesList = (query: ListQuery = {}) => useList<VacancyItem>('vacancies', query)
export const useVacancyItem = (slug: MaybeRefOrGetter<string>) => useItem<VacancyItem>('vacancies', slug)

export const useTendersList = (query: ListQuery = {}) => useList<TenderItem>('tenders', query)
export const useTenderItem = (slug: MaybeRefOrGetter<string>) => useItem<TenderItem>('tenders', slug)
