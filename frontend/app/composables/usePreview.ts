const MAX_AGE = 86400

const LOCALE = /^\/(?:ru|uz)(?=\/|$)/

export function usePreview() {
  const token = useCookie('preview', { maxAge: MAX_AGE, sameSite: 'lax' })
  const path = useCookie('preview_path', { maxAge: MAX_AGE, sameSite: 'lax' })
  const route = useRoute()

  const address = (value: string): string => value.replace(LOCALE, '') || '/'

  const active = computed(() => Boolean(token.value) && path.value === address(route.path))

  return { token, path, address, active }
}
