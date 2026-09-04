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

export interface CoverageCity {
  id: number
  name: string
  center: [number, number]
}

interface RegionRow {
  id: number
  name: string
  center: [number, number] | null
}

export function useCoverage() {
  const { locale } = useI18n()
  const { $api } = useNuxtApp()

  return useAsyncData(
    'coverage',
    async () => {
      const [layers, regions] = await Promise.all([
        $api<ApiResponse<CoverageLayer[]>>('/coverage'),
        $api<ApiResponse<RegionRow[]>>('/categories/regions'),
      ])

      return {
        layers: layers.data,
        cities: regions.data
          .filter((region): region is RegionRow & { center: [number, number] } => region.center !== null)
          .map(({ id, name, center }) => ({ id, name, center })),
      }
    },
    { watch: [locale], default: () => ({ layers: [] as CoverageLayer[], cities: [] as CoverageCity[] }) },
  )
}

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
