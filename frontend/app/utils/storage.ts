/**
 * The API hands out absolute addresses on its own host. A browser ignores the
 * `download` attribute across origins, so a file linked that way opens instead
 * of saving. The site proxies `/storage/*` to the same place, and served from
 * its own origin the attribute works again.
 */
export function storageUrl(url: string | null | undefined): string {
  if (!url) {
    return ''
  }

  const at = url.indexOf('/storage/')

  return at === -1 ? url : url.slice(at)
}
