import type { ApiResponse } from '~/types/api'

export interface CoverageFeature {
  type: 'Feature'
  geometry: {
    type: 'Polygon' | 'LineString'
    coordinates: number[][] | number[][][]
  }
}

export interface CoverageShapes {
  type: 'FeatureCollection'
  features: CoverageFeature[]
}

export interface CoverageLayer {
  key: string
  name: string
  color: string
  features: number
  url: string
}

export function useCoverage() {
  const { locale } = useI18n()
  const { $api } = useNuxtApp()

  return useAsyncData(
    'coverage',
    () => $api<ApiResponse<CoverageLayer[]>>('/coverage').then(response => response.data),
    { watch: [locale], default: () => [] as CoverageLayer[] },
  )
}

/**
 * The map draws one layer at a time, so a collection is fetched when it is
 * first switched to and kept for the rest of the visit.
 */
const shapes = new Map<string, Promise<CoverageShapes>>()

export function useCoverageShapes() {
  const { $api } = useNuxtApp()

  return (key: string): Promise<CoverageShapes> => {
    if (!shapes.has(key)) {
      shapes.set(key, $api<CoverageShapes>(`/coverage/${key}`))
    }

    return shapes.get(key)!
  }
}
