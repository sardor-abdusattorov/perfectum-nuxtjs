export default defineEventHandler(async (event) => {
  const target = apiTarget(event)
  const path = new URL(event.path, 'http://origin').pathname

  if (!path.startsWith('/storage/') || event.path.includes('..')) {
    throw createError({ statusCode: 404 })
  }

  return proxyRequest(event, `${target.replace(/\/api\/v1$/, '')}${path}`, {
    onResponse(proxied, response) {
      if (response.ok) {
        setResponseHeader(proxied, 'cache-control', 'public, max-age=604800, immutable')
      }
    },
  })
})
