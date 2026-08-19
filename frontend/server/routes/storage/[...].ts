/**
 * The bodies carried over from the old site link their documents and pictures
 * as /storage/… — an address the API serves, not this origin. Passing it
 * through keeps every such link alive on the site's own domain.
 *
 * The names are content hashes, so a file never changes under its name and a
 * long browser cache is safe.
 */
export default defineEventHandler(async (event) => {
  const target = useRuntimeConfig(event).apiBase

  if (!target) {
    throw createError({ statusCode: 500, statusMessage: 'API base is not configured' })
  }

  setResponseHeader(event, 'cache-control', 'public, max-age=604800, immutable')

  return proxyRequest(event, `${target.replace(/\/api\/v1\/?$/, '')}${event.path}`)
})
