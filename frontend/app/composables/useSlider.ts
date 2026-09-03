import Swiper from 'swiper'
import { A11y, Autoplay, FreeMode, Navigation, Pagination, Scrollbar } from 'swiper/modules'
import type { SwiperOptions } from 'swiper/types'
import type { ShallowRef } from 'vue'

const MODULES = [A11y, Autoplay, FreeMode, Navigation, Pagination, Scrollbar]

function slideRole(track: Element | null): Pick<SwiperOptions, 'a11y'> {
  return track?.tagName === 'UL' || track?.tagName === 'OL'
    ? { a11y: { slideRole: 'listitem' } }
    : {}
}

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
