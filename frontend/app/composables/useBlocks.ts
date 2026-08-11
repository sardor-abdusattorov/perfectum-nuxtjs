import type { ApiResponse, PageBlocks } from '~/types/api'

type Block = Record<string, any>

export function useBlocks(page: string) {
  const { locale } = useI18n()
  const { $api } = useNuxtApp()

  return useAsyncData<PageBlocks>(
    () => `blocks:${page}`,
    () => $api<ApiResponse<PageBlocks>>(`/blocks/${page}`).then(response => response.data),
    { watch: [locale] },
  )
}

/**
 * One block of a page. Returns an empty object until the payload arrives, so
 * a component reads `block.value.title` without guarding every access.
 */
export function useBlock(page: string, key: string) {
  const { data } = useBlocks(page)

  return computed<Block>(() => data.value?.blocks?.[key] ?? {})
}

/**
 * A repeatable inside a block, with the items an editor switched off removed.
 */
export function published(items: unknown): Block[] {
  if (!Array.isArray(items)) {
    return []
  }

  return items.filter(item => item && typeof item === 'object' && (item as Block).status !== false)
}
