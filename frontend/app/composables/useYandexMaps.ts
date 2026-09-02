const LANGS: Record<string, string> = { ru: 'ru_RU', uz: 'uz_UZ', en: 'en_US' }

let loading: Promise<any> | null = null

export function useYandexMaps() {
  const { locale } = useI18n()
  const settings = useSiteSettings()

  return (): Promise<any> => {
    const ymaps = (window as any).ymaps

    if (ymaps) {
      return new Promise(resolve => ymaps.ready(() => resolve(ymaps)))
    }

    if (loading) {
      return loading
    }

    const key = settings.value?.maps.yandex_key
    const src = `https://api-maps.yandex.ru/2.1/?${key ? `apikey=${encodeURIComponent(key)}&` : ''}lang=${LANGS[locale.value] ?? 'ru_RU'}`

    loading = new Promise((resolve, reject) => {
      const script = document.createElement('script')

      script.src = src
      script.async = true
      script.addEventListener('load', () => {
        const loaded = (window as any).ymaps

        loaded.ready(() => resolve(loaded))
      })
      script.addEventListener('error', () => {
        loading = null
        script.remove()
        reject(new Error('ymaps'))
      })
      document.head.appendChild(script)
    })

    return loading
  }
}
