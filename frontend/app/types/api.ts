export interface Seo {
  title: string
  description: string
  keywords: string
  robots: string
  og_image: string | null
}

export interface Settings {
  locale: string
  locales: string[]
  seo: Seo
  metrics: { enabled: boolean }
  site: Record<string, string | null>
}

export interface MenuItem {
  id: number
  name: string
  url: string | null
  column: number | null
  target: string | null
  children: MenuItem[]
}

export type MenuLocation = 'header' | 'footer'

export type Menus = Record<MenuLocation, MenuItem[]>

export type Translations = Record<string, Record<string, string>>

export interface Page {
  page: string
  seo: Seo
  blocks: Record<string, Record<string, unknown>>
}

export interface Metrics {
  yandex?: string
  google?: string
}

export interface ApiResponse<T> {
  data: T
}
