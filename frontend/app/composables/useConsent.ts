const COOKIE_NAME = 'cookie_consent'
const YEAR = 60 * 60 * 24 * 365

/**
 * The notice says what the site does, it does not switch the counters on and
 * off — those load with the page for everyone, in the server-rendered HTML.
 * Wiring analytics to this button cost the site most of its statistics: hardly
 * anyone presses it, and everyone who does not was never counted at all.
 */
export function useConsent() {
  const consent = useCookie<'accepted' | null>(COOKIE_NAME, {
    maxAge: YEAR,
    sameSite: 'lax',
    path: '/',
  })

  const visible = computed(() => consent.value !== 'accepted')

  function accept(): void {
    consent.value = 'accepted'
  }

  onMounted(() => {
    try {
      if (consent.value !== 'accepted' && localStorage.getItem(COOKIE_NAME) === 'accepted') {
        accept()
      }
    }
    catch {}
  })

  return { visible, accept }
}
