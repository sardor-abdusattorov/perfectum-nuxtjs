import Swiper from 'swiper/bundle'
import type { SwiperOptions } from 'swiper/types'

/**
 * The slides come from the API and change whenever a tab or a chip is clicked,
 * so the slider is owned by the component that renders them: a plugin that
 * queried the DOM once left the arrows pointing at slides that no longer
 * existed.
 */
export function useSlider(
  element: Readonly<ShallowRef<HTMLElement | null>>,
  options: SwiperOptions,
  source: () => unknown,
): void {
  let slider: Swiper | null = null

  onMounted(() => {
    if (element.value) {
      slider = new Swiper(element.value, options)
    }
  })

  onBeforeUnmount(() => {
    slider?.destroy(true, true)
    slider = null
  })

  watch(source, async () => {
    await nextTick()
    slider?.update()
    slider?.slideTo(0, 0)
  })
}
