const FROM = 1000
const TO = 970
const MAX_TILT = -5
const DURATION = 1300

/**
 * Hero speed gauge: the needle rocks between two readings while the counter
 * follows it, looping forever.
 */
export function useHeroGauge(root: Ref<HTMLElement | null>) {
  const frames: number[] = []

  onMounted(() => {
    const gauges = Array.from(root.value?.querySelectorAll<HTMLElement>('.hero__gauge') ?? [])

    gauges.forEach((gauge) => {
      const needle = gauge.querySelector<HTMLElement>('.hero__gauge-needle')
      const value = gauge.querySelector('.hero__gauge-value')

      if (!needle) {
        return
      }

      let start: number | null = null

      const frame = (time: number) => {
        if (start === null) {
          start = time
        }

        const elapsed = (time - start) % (DURATION * 2)
        let progress = elapsed < DURATION ? elapsed / DURATION : 1 - (elapsed - DURATION) / DURATION
        progress = progress * progress * (3 - 2 * progress)

        needle.style.transform = `rotate(${MAX_TILT * progress}deg)`

        if (value) {
          value.textContent = String(Math.round(FROM + (TO - FROM) * progress)).replace(
            /\B(?=(\d{3})+(?!\d))/g,
            ' ',
          )
        }

        frames.push(requestAnimationFrame(frame))
      }

      frames.push(requestAnimationFrame(frame))
    })
  })

  onBeforeUnmount(() => {
    frames.forEach((id) => cancelAnimationFrame(id))
    frames.length = 0
  })
}
