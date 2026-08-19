/**
 * The bodies carried over from the old site link their documents and pictures
 * as /storage/… — an address the API serves, not this origin. Passing it
 * through keeps every such link alive on the site's own domain.
 *
 * The names are content hashes, so a file never changes under its name and a
 * long browser cache is safe — but only on a hit: a 404 cached for a week
 * would outlive the file arriving.
 */
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
