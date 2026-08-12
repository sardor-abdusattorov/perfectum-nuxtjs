export interface Store {
  name: string
  url: string
  icon: string
}

export function useStores() {
  const setting = useSetting()

  return computed<Store[]>(() => [
    { name: 'App Store', url: setting('app_store_url'), icon: 'apple' },
    { name: 'Google Play', url: setting('google_play_url'), icon: 'google-play' },
  ].filter(store => store.url))
}
