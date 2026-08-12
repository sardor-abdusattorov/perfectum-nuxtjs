const SINGLE_PARAGRAPH = /^<p(?:\s[^>]*)?>([\s\S]*)<\/p>$/i
const COLOR_SPAN = /<span\b[^>]*\bdata-color="([^"]+)"[^>]*>/gi

export function rich(value: unknown, accents: Record<string, string> = {}): string {
  const html = String(value ?? '').trim()
  const inner = html.match(SINGLE_PARAGRAPH)?.[1]
  const body = inner !== undefined && !/<p[\s>]/i.test(inner) ? inner : html

  return body.replace(COLOR_SPAN, (span, name: string) => {
    const className = accents[name]

    return className ? `<span class="${className}">` : span
  })
}
