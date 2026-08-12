const LOCALES: Record<string, string> = { ru: 'ru-RU', uz: 'uz-UZ', en: 'en-GB' }

export function dateShort(value: string | null | undefined): string {
  if (!value) {
    return ''
  }

  const [year, month, day] = value.split('-')

  return `${day}.${month}.${year}`
}

export function dateLong(value: string | null | undefined, locale: string): string {
  if (!value) {
    return ''
  }

  return new Intl.DateTimeFormat(LOCALES[locale] ?? 'ru-RU', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  }).format(new Date(value))
}
