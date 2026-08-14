import type { ApiResponse } from '~/types/api'

export interface CoverageFeature {
  type: 'Feature'
  geometry: {
    type: 'Polygon' | 'LineString'
    coordinates: number[][] | number[][][]
  }
}

export interface CoverageLayer {
  key: string
  name: string
  color: string
  geojson: { type: 'FeatureCollection', features: CoverageFeature[] } | null
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
