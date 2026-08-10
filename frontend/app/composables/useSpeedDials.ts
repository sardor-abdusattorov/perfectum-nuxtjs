const SIZE = 450
const STROKE = 30
const GAP = 80
const CX = SIZE / 2
const CY = SIZE / 2
const R = (SIZE - STROKE) / 2 - 6
const START = 180 + GAP / 2
const SWEEP = 360 - GAP
const DURATION = 1400

const NEEDLE_BASE = { x: 8.7, y: 11.2 }
const NEEDLE_TIP = { x: 164.577, y: 70.0769 }
const NEEDLE_NATURAL =
  (Math.atan2(NEEDLE_TIP.x - NEEDLE_BASE.x, -(NEEDLE_TIP.y - NEEDLE_BASE.y)) * 180) / Math.PI
const CAP_OFFSET = ((STROKE / 2 / R) * 180) / Math.PI

function polar(angle: number): [number, number] {
  const radians = (angle * Math.PI) / 180

  return [CX + R * Math.sin(radians), CY - R * Math.cos(radians)]
}

function arcPath(from: number, to: number): string {
  const [x0, y0] = polar(from)
  const [x1, y1] = polar(to)
  const large = to - from > 180 ? 1 : 0

  return `M ${x0} ${y0} A ${R} ${R} 0 ${large} 1 ${x1} ${y1}`
}

/**
 * Speedometer dials in the features section: an SVG arc plus a needle that
 * sweep between `data-from` and `data-to` while the dial is on screen.
 */
export function useSpeedDials(root: Ref<HTMLElement | null>) {
  onMounted(() => {
    const dials = Array.from(root.value?.querySelectorAll<HTMLElement>('.features__dial') ?? [])

    if (!dials.length) {
      return
    }

    const frames = new Map<HTMLElement, number>()

    const render = (dial: HTMLElement, value: number) => {
      const fraction = Math.max(0, Math.min(1, value / Number(dial.dataset.max)))
      const fillEnd = START + SWEEP * fraction

      dial.querySelector('.features__dial-track')?.setAttribute('d', arcPath(START, START + SWEEP))
      dial
        .querySelector('.features__dial-fill')
        ?.setAttribute('d', fraction <= 0.001 ? '' : arcPath(START, fillEnd))

      const needle = dial.querySelector<HTMLElement>('.features__dial-needle')

      if (needle) {
        needle.style.transform = `rotate(${fillEnd - NEEDLE_NATURAL + CAP_OFFSET}deg)`
      }

      const readout = dial.querySelector('.features__readout-value')

      if (readout) {
        readout.textContent = value.toFixed(2)
      }
    }

    const animate = (dial: HTMLElement) => {
      const from = Number(dial.dataset.from)
      const to = Number(dial.dataset.to)
      let start: number | null = null

      const frame = (time: number) => {
        if (start === null) {
          start = time
        }

        const elapsed = (time - start) % (DURATION * 2)
        let progress = elapsed < DURATION ? elapsed / DURATION : 1 - (elapsed - DURATION) / DURATION
        progress = progress * progress * (3 - 2 * progress)

        render(dial, from + (to - from) * progress)
        frames.set(dial, requestAnimationFrame(frame))
      }

      frames.set(dial, requestAnimationFrame(frame))
    }

    dials.forEach((dial) => render(dial, Number(dial.dataset.from)))

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          const dial = entry.target as HTMLElement

          if (entry.isIntersecting && !frames.has(dial)) {
            animate(dial)
          }
          else if (!entry.isIntersecting && frames.has(dial)) {
            cancelAnimationFrame(frames.get(dial)!)
            frames.delete(dial)
          }
        })
      },
      { threshold: 0.3 },
    )

    dials.forEach((dial) => observer.observe(dial))

    onBeforeUnmount(() => {
      observer.disconnect()
      frames.forEach((id) => cancelAnimationFrame(id))
      frames.clear()
    })
  })
}
