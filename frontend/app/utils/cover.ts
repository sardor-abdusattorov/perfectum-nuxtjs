/**
 * The media blocks carry a colour panel of their own, so a photo is laid
 * over it rather than replacing the markup.
 */
export function cover(url?: string | null): Record<string, string> | undefined {
  return url
    ? { backgroundImage: `url(${url})`, backgroundSize: 'cover', backgroundPosition: 'center' }
    : undefined
}
