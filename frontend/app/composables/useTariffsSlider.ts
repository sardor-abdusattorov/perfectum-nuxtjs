import Swiper from 'swiper'
import { Navigation } from 'swiper/modules'

/**
 * Home tariffs carousel: a Swiper rail plus the "Домашний интернет / Мобильная
 * связь" tabs that filter slides by their `data-group`, and the connect
 * buttons that feed the tariff modal.
 */
export function useTariffsSlider(root: Ref<HTMLElement | null>) {
  const modal = useTariffModal()

  onMounted(() => {
    const container = root.value?.querySelector<HTMLElement>('.tariffs__swiper')

    if (!container) {
      return
    }

    const slider = new Swiper(container, {
      modules: [Navigation],
      slidesPerView: 2,
      spaceBetween: 24,
      watchOverflow: true,
      navigation: {
        prevEl: root.value!.querySelector<HTMLElement>('.slider-arrow_prev'),
        nextEl: root.value!.querySelector<HTMLElement>('.slider-arrow_next'),
      },
      breakpoints: {
        // the phone frame fits two compact cards side by side, not one
        0: { slidesPerView: 2, spaceBetween: 12 },
        768: { slidesPerView: 2, spaceBetween: 16 },
        1200: { slidesPerView: 3, spaceBetween: 20 },
        1400: { slidesPerView: 4, spaceBetween: 24 },
      },
    })

    const tabs = Array.from(root.value!.querySelectorAll<HTMLElement>('.tariffs__tab'))
    const slides = Array.from(container.querySelectorAll<HTMLElement>('.swiper-slide'))

    const showGroup = (group: string) => {
      slides.forEach((slide) => {
        slide.classList.toggle('is-hidden', !(group === 'all' || slide.dataset.group === group))
      })

      slider.update()
      slider.slideTo(0, 0)
    }

    const onTabClick = (event: Event) => {
      const tab = event.currentTarget as HTMLElement

      tabs.forEach((item) => item.classList.remove('tariffs__tab_active'))
      tab.classList.add('tariffs__tab_active')
      showGroup(tab.dataset.tab ?? 'all')
    }

    tabs.forEach((tab) => tab.addEventListener('click', onTabClick))
    showGroup('home')

    const onConnectClick = (event: Event) => {
      const trigger = (event.target as HTMLElement).closest<HTMLElement>('.js-tariff-connect')

      if (!trigger) {
        return
      }

      event.preventDefault()
      modal.open({
        name: trigger.dataset.name ?? '',
        price: trigger.dataset.price ?? '',
        period: trigger.dataset.period ?? '',
      })
    }

    root.value!.addEventListener('click', onConnectClick)

    onBeforeUnmount(() => {
      tabs.forEach((tab) => tab.removeEventListener('click', onTabClick))
      root.value?.removeEventListener('click', onConnectClick)
      slider.destroy(true, true)
    })
  })
}
