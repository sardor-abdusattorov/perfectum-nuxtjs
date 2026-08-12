export interface Seo {
  title: string
  description: string
  keywords: string
  robots: string
  og_image: string | null
}

export interface PageSeo {
  title: string | null
  description: string | null
  og_image: string | null
  indexed: boolean
}

export interface Settings {
  locale: string
  locales: string[]
  seo: Seo
  metrics: { enabled: boolean }
  site: Record<string, string | null>
  pages: Record<string, PageSeo>
}

export interface MenuItem {
  id: number
  name: string
  url: string | null
  target: string | null
  children: MenuItem[]
}

export type MenuLocation = 'header' | 'footer'

export type Menus = Partial<Record<MenuLocation, MenuItem[]>>

export type Translations = Record<string, string>

export interface PageBlocks {
  page: string
  blocks: Record<string, Record<string, any>>
}

export interface PageContent {
  slug: string
  title: string
  content: string
  image: string | null
  seo: Seo
  updated_at: string | null
}

export interface Social {
  name: string
  svg: string | null
  url: string
}

export interface Site {
  settings: Settings
  menus: Menus
  socials: Social[]
  translations: Translations
}

export interface Metrics {
  yandex?: string
  google?: string
}

export interface ApiResponse<T> {
  data: T
}

export interface TariffButton {
  icon: string | null
  name: string
  url: string
  type: 'link' | 'tel'
}

export interface TariffSummary {
  name: string
  price: string
  price_currency: string
  price_period: string
  modal_image: string | null
  buttons: TariffButton[]
}
