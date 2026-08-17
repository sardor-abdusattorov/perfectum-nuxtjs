export interface Store {
  name: string
  url: string
  icon: string
  width: number
  height: number
}

export function useStores() {
  const setting = useSetting()

  return computed<Store[]>(() => [
    { name: 'App Store', url: setting('app_store_url'), icon: 'apple', width: 98, height: 23 },
    { name: 'Google Play', url: setting('google_play_url'), icon: 'google-play', width: 116, height: 26 },
  ].filter(store => store.url))
}
