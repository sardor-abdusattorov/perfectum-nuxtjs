/**
 * The preview link from the admin arrives with the token in the address. The
 * address is for people, so the token moves into a cookie — living exactly as
 * long as the token itself — and the visitor lands on the clean URL. Every
 * later request, reloads included, carries the token from the cookie instead.
 */
export default defineNuxtRouteMiddleware((to) => {
  const token = to.query.preview

  if (typeof token !== 'string' || !token) {
    return
  }

  const cookie = useCookie('preview', { maxAge: 86400, sameSite: 'lax' })
  cookie.value = token

  const query = { ...to.query }
  delete query.preview

  return navigateTo({ path: to.path, query, hash: to.hash }, { replace: true })
})
