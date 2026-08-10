const SPEED_PX_PER_SECOND = 60

/**
 * Endless horizontal ticker: the track is duplicated until it overflows the
 * viewport, then translated back to zero once a full copy has scrolled past.
 */
export function useMarquee(root: Ref<HTMLElement | null>) {
  let frame: number | null = null

  onMounted(() => {
    const marquee = root.value
    const track = marquee?.querySelector<HTMLElement>('.marquee__track')

    if (!marquee || !track) {
      return
    }

    const original = track.innerHTML
    const copyWidth = track.scrollWidth

    while (track.scrollWidth < marquee.offsetWidth + copyWidth) {
      track.insertAdjacentHTML('beforeend', original)
    }

    let offset = 0
    let last: number | null = null

    const step = (now: number) => {
      if (last !== null) {
        offset += (SPEED_PX_PER_SECOND * (now - last)) / 1000

        if (offset >= copyWidth) {
          offset -= copyWidth
        }

        track.style.transform = `translateX(${-offset}px)`
      }

      last = now
      frame = requestAnimationFrame(step)
    }

    const start = () => {
      if (frame === null) {
        last = null
        frame = requestAnimationFrame(step)
      }
    }

    const stop = () => {
      if (frame !== null) {
        cancelAnimationFrame(frame)
        frame = null
      }
    }

    marquee.addEventListener('mouseenter', stop)
    marquee.addEventListener('mouseleave', start)
    start()

    onBeforeUnmount(() => {
      stop()
      marquee.removeEventListener('mouseenter', stop)
      marquee.removeEventListener('mouseleave', start)
    })
  })
}
