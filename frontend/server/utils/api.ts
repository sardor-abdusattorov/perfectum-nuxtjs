import type { H3Event } from 'h3'

/**
 * The address of the API is the server's alone, and the proxies below have
 * nothing to forward to without it: NUXT_API_BASE lives in frontend/.env, not
 * in the API's own .env.
 */
export function apiTarget(event: H3Event): string {
  const base = useRuntimeConfig(event).apiBase

  if (!base) {
    throw createError({
      statusCode: 500,
      statusMessage: 'NUXT_API_BASE is not set in frontend/.env',
    })
  }

  return base.replace(/\/+$/, '')
}
