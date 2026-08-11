export type NetworkSection = '5g' | 'cdma'

export function useNetwork() {
  const route = useRoute()
  const localePath = useLocalePath()

  const cdmaRoot = localePath('/cdma')

  return computed<NetworkSection>(() =>
    route.path === cdmaRoot || route.path.startsWith(`${cdmaRoot}/`) ? 'cdma' : '5g',
  )
}
