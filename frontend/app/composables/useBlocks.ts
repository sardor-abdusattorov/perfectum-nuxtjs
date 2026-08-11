import type { ApiResponse, PageBlocks } from '~/types/api'

type Block = Record<string, any>

export function useBlocks(page: string) {
  const { locale } = useI18n()
  const { $api } = useNuxtApp()

  return useAsyncData<PageBlocks>(
    `blocks:${page}`,
    () => $api<ApiResponse<PageBlocks>>(`/blocks/${page}`).then(response => response.data),
    { watch: [locale] },
  )
}

export function useBlock(page: string, key: string) {
  const { data } = useNuxtData<PageBlocks>(`blocks:${page}`)

  return computed<Block>(() => data.value?.blocks?.[key] ?? {})
}

export function published(items: unknown): Block[] {
  if (!Array.isArray(items)) {
    return []
  }

  return items.filter(item => item && typeof item === 'object' && (item as Block).status !== false)
}
