const SINGLE_PARAGRAPH = /^<p(?:\s[^>]*)?>([\s\S]*)<\/p>$/i

export function rich(value: unknown): string {
  const html = String(value ?? '').trim()
  const inner = html.match(SINGLE_PARAGRAPH)?.[1]

  return inner !== undefined && !/<p[\s>]/i.test(inner) ? inner : html
}
