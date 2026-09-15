import type { MaybeRefOrGetter } from 'vue'

/**
 * Какой раздел читают сейчас. Панель вкладок липкая, поэтому решает не верх
 * окна, а линия под ней — та же, от которой отсчитывает scroll-margin-top у
 * разделов.
 *
 * Порядок берётся из вёрстки, а не из списка вкладок: их порядок задаёт
 * менеджер в админке и совпадать с раскладкой страницы он не обязан.
 */
export function useSectionSpy(targets: MaybeRefOrGetter<string[]>, offset = 180) {
  const current = ref('')

  const first = computed(() => toValue(targets)[0] ?? '')
  const active = computed(() => current.value || first.value)

  let frame = 0

  /**
   * Нижние разделы часто не дотягивают до высоты экрана: страница упирается в
   * конец раньше, чем верх нужного раздела доедет до линии. Поэтому нажатая
   * вкладка держится, пока человек сам не тронет страницу — клик это
   * высказанное намерение, а не догадка по координатам.
   */
  let pinned = false

  function sections(): HTMLElement[] {
    return toValue(targets)
      .map(target => document.querySelector<HTMLElement>(target))
      .filter((element): element is HTMLElement => element !== null)
      .sort((one, other) => one.getBoundingClientRect().top - other.getBoundingClientRect().top)
  }

  function measure(): void {
    const found = sections()

    if (!found.length) {
      return
    }

    let reached = found[0]!

    for (const section of found) {
      if (section.getBoundingClientRect().top - offset <= 0) {
        reached = section
      }
    }

    // Дальше крутить некуда, а нижние разделы так и не дошли до линии — значит
    // читают последний из тех, что уже видно, а не тот, что случайно её задел.
    if (window.scrollY + window.innerHeight >= document.documentElement.scrollHeight - 2) {
      for (const section of found) {
        if (section.getBoundingClientRect().top < window.innerHeight) {
          reached = section
        }
      }
    }

    current.value = `#${reached.id}`
  }

  function onScroll(): void {
    if (pinned) {
      return
    }

    cancelAnimationFrame(frame)
    frame = requestAnimationFrame(measure)
  }

  function release(): void {
    pinned = false
  }

  function activate(target: string): void {
    current.value = target
    pinned = true
  }

  onMounted(() => {
    const hash = location.hash

    if (hash && toValue(targets).includes(hash)) {
      activate(hash)
    }
    else {
      measure()
    }

    window.addEventListener('scroll', onScroll, { passive: true })
    window.addEventListener('resize', onScroll, { passive: true })

    for (const event of ['wheel', 'touchmove', 'keydown', 'mousedown'] as const) {
      window.addEventListener(event, release, { passive: true })
    }
  })

  onBeforeUnmount(() => {
    cancelAnimationFrame(frame)

    window.removeEventListener('scroll', onScroll)
    window.removeEventListener('resize', onScroll)

    for (const event of ['wheel', 'touchmove', 'keydown', 'mousedown'] as const) {
      window.removeEventListener(event, release)
    }
  })

  return { active, activate }
}
