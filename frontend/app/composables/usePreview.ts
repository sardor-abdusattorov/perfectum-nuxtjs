const MAX_AGE = 86400

const LOCALE = /^\/(?:ru|uz)(?=\/|$)/

/**
 * A token unlocks one record, so preview mode is a property of one address —
 * not of the whole visit. The cookie therefore remembers the address the link
 * was made for, and the strip and the noindex only apply while the visitor is
 * standing on it. Walking away is how you leave preview; there is nothing to
 * switch off.
 *
 * The address is kept without its locale prefix, so the same draft stays in
 * preview after a switch between ru and uz.
 */
export function usePreview() {
  const token = useCookie('preview', { maxAge: MAX_AGE, sameSite: 'lax' })
  const path = useCookie('preview_path', { maxAge: MAX_AGE, sameSite: 'lax' })
  const route = useRoute()

  const address = (value: string): string => value.replace(LOCALE, '') || '/'

  const active = computed(() => Boolean(token.value) && path.value === address(route.path))

  return { token, path, address, active }
}
