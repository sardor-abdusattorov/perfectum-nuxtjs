export type NetworkSection = '5g' | 'cdma'

export function useNetwork() {
  const route = useRoute()
  const localePath = useLocalePath()

  const cdmaRoot = computed(() => localePath('/cdma'))

  return computed<NetworkSection>(() =>
    route.path === cdmaRoot.value || route.path.startsWith(`${cdmaRoot.value}/`) ? 'cdma' : '5g',
  )
}
