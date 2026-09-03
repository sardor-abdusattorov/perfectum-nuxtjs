const DELAY = 250
const HOLD = 250
const FAILSAFE = 6000

export function useLocaleLoader() {
  const active = useState('locale-loader', () => false)
  const run = useState('locale-loader-run', () => 0)
  const shownAt = useState('locale-loader-shown-at', () => 0)

  function show(): void {
    const stamp = ++run.value

    after(DELAY, stamp, () => {
      shownAt.value = Date.now()
      active.value = true
    })

    after(FAILSAFE, stamp, () => (active.value = false))
  }

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
