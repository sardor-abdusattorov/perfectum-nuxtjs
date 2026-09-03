import type { H3Event } from 'h3'

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
