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
