/**
 * Chromium ships no ICU data for uz-UZ, so `Intl` prints "M03" in the browser
 * where the SSR runtime printed "mart" — the month names and the date pattern
 * are site translations instead, and the string is built from the ISO parts so
 * no timezone can shift the day either.
 */
export function useDates() {
  const t = useT()

  function names(key: string): string[] {
    return t(key).split(',').map(name => name.trim())
  }

  function monthName(month: number | string): string {
    return names('date.months')[Number(month) - 1] ?? ''
  }

  function long(value: string | null | undefined): string {
    if (!value) {
      return ''
    }

    const [year, month, day] = value.slice(0, 10).split('-')

    if (!year || !month || !day) {
      return ''
    }

    return t('date.long')
      .replace('{day}', String(Number(day)))
      .replace('{month}', names('date.months_of')[Number(month) - 1] ?? '')
      .replace('{year}', year)
  }

  return { long, monthName }
}
