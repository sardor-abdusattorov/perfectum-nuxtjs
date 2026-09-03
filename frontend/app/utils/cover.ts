export function cover(url?: string | null): Record<string, string> | undefined {
  return url
    ? { backgroundImage: `url(${url})`, backgroundSize: 'cover', backgroundPosition: 'center' }
    : undefined
}
