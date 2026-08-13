const MIN_VISIBLE = 600
const SETTLE = 200
const FAILSAFE = 6000

export function useLocaleLoader() {
  const active = useState('locale-loader', () => false)
  const shownAt = useState('locale-loader-shown-at', () => 0)

  /**
   * The stamp is taken on the click, not in a watcher, so the minimum
   * hold still counts when the page settles before Vue flushes; it also
   * identifies the run, so a timer from an earlier switch cannot close
   * the overlay a later one has just opened.
   */
  function show(): void {
    shownAt.value = Date.now()
    active.value = true

    close(FAILSAFE, shownAt.value)
  }

  /**
   * Called when the new page has finished loading. The fade starts no
   * sooner than SETTLE after that, so the content swap and the scroll
   * jump repaint fully behind the veil.
   */
  function hide(): void {
    if (active.value) {
      close(Math.max(SETTLE, MIN_VISIBLE - (Date.now() - shownAt.value)), shownAt.value)
    }
  }

  function close(wait: number, stamp: number): void {
    setTimeout(() => {
      if (shownAt.value === stamp) {
        active.value = false
      }
    }, wait)
  }

  return { active, show, hide }
}
