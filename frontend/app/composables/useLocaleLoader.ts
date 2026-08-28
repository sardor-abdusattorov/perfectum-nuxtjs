const DELAY = 250
const HOLD = 250
const FAILSAFE = 6000

export function useLocaleLoader() {
  const active = useState('locale-loader', () => false)
  const run = useState('locale-loader-run', () => 0)
  const shownAt = useState('locale-loader-shown-at', () => 0)

  /**
   * A switch that lands within DELAY shows nothing at all — the veil is
   * there for a slow answer, not as a ceremony. The counter identifies the
   * run, so a timer left over from an earlier switch cannot touch the veil
   * a later one owns.
   */
  function show(): void {
    const stamp = ++run.value

    after(DELAY, stamp, () => {
      shownAt.value = Date.now()
      active.value = true
    })

    after(FAILSAFE, stamp, () => (active.value = false))
  }

  /**
   * Called when the new page has loaded. A veil already up stays for HOLD,
   * so a switch that only just missed the delay does not strobe.
   */
  function hide(): void {
    const wait = active.value ? HOLD - (Date.now() - shownAt.value) : 0
    const stamp = ++run.value

    if (wait <= 0) {
      active.value = false

      return
    }

    after(wait, stamp, () => (active.value = false))
  }

  function after(wait: number, stamp: number, done: () => void): void {
    setTimeout(() => {
      if (run.value === stamp) {
        done()
      }
    }, wait)
  }

  return { active, show, hide }
}
