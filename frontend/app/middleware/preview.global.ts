/**
 * The preview link from the admin arrives with the token in the address. The
 * address is for people, so the token moves into a cookie — together with the
 * page it was minted for — and the visitor lands on the clean URL. Every later
 * request, reloads included, carries the token from the cookie instead.
 */
export default defineNuxtRouteMiddleware((to) => {
  const value = to.query.preview

  if (typeof value !== 'string' || !value) {
    return
  }

  const { token, path, address } = usePreview()

  token.value = value
  path.value = address(to.path)

  const query = { ...to.query }
  delete query.preview

  return navigateTo({ path: to.path, query, hash: to.hash }, { replace: true })
})
