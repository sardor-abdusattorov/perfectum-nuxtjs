export type HelpSection = 'faq' | 'numbers' | 'contact'

/**
 * The switches on Настройки → Помощь take the section off the site, not just
 * out of the sidebar: a hidden link is not a closed page, and the address is
 * still in the browser history of everyone who has been there.
 */
export function useHelpSection(key: HelpSection): void {
  const settings = useSiteSettings()

  if (settings.value?.help?.[key] === false) {
    throw createError({ statusCode: 404, statusMessage: 'Not Found', fatal: true })
  }
}
