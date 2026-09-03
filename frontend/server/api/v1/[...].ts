export default defineEventHandler(async (event) => {
  const target = apiTarget(event)
  const path = (event.context.params?._ ?? '').replace(/^\/+/, '')
  const { search } = getRequestURL(event)
  const visitor = getRequestIP(event, { xForwardedFor: true })

  return proxyRequest(event, `${target}/${path}${search}`, {
    headers: {
      ...(visitor ? { 'x-forwarded-for': visitor } : {}),
      'x-forwarded-proto': getRequestProtocol(event),
    },
  })
})
