/**
 * The browser talks only to this origin: requests it makes in the page are
 * proxied here and reach the API from the server, which drops CORS and its
 * preflights entirely and lets the API host stay off the public internet.
 * Rendering still calls the API directly — it is already on the server, and a
 * hop through here would only cost a round-trip.
 */
export default defineEventHandler(async (event) => {
  const target = useRuntimeConfig(event).apiBase

  if (!target) {
    throw createError({ statusCode: 500, statusMessage: 'API base is not configured' })
  }

  const path = (event.context.params?._ ?? '').replace(/^\/+/, '')
  const { search } = getRequestURL(event)

  return proxyRequest(event, `${target.replace(/\/+$/, '')}/${path}${search}`, {
    headers: {
      'x-forwarded-for': getRequestIP(event, { xForwardedFor: true }) ?? '',
      'x-forwarded-proto': getRequestProtocol(event),
    },
  })
})
