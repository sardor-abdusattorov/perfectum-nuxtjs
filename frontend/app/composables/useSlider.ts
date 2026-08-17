import Swiper from 'swiper'
import { A11y, Autoplay, FreeMode, Navigation, Pagination, Scrollbar } from 'swiper/modules'
import type { SwiperOptions } from 'swiper/types'
import type { ShallowRef } from 'vue'

/**
 * The bundle build registers every effect and behaviour Swiper ships; these six
 * are the ones the site's sliders ask for.
 */
const MODULES = [A11y, Autoplay, FreeMode, Navigation, Pagination, Scrollbar]

/**
 * A track written as a `<ul>` may only hold list items, so Swiper's own
 * `role="group"` on each slide makes that markup invalid — and a `listitem`
 * outside a list is just as wrong, which is why the track decides.
 */
function slideRole(track: Element | null): Pick<SwiperOptions, 'a11y'> {
  return track?.tagName === 'UL' || track?.tagName === 'OL'
    ? { a11y: { slideRole: 'listitem' } }
    : {}
}

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
      slider = new Swiper(element.value, {
        modules: MODULES,
        ...slideRole(element.value.querySelector('.swiper-wrapper')),
        ...options,
      })
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
