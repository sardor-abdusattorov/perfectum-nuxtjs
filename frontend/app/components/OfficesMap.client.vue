<script setup lang="ts">
import type { Office } from '~/composables/useOffices'

const props = defineProps<{ points: Office[], active: number | null }>()
const emit = defineEmits<{ select: [id: number] }>()

const t = useT()
const { locale } = useI18n()
const config = useRuntimeConfig()

const canvas = useTemplateRef('canvas')
const failed = ref(false)

let map: any = null
const markers = new Map<number, any>()

const LANGS: Record<string, string> = { ru: 'ru_RU', uz: 'uz_UZ', en: 'en_US' }

const PIN = '<svg viewBox="0 0 32 40" fill="none" xmlns="http://www.w3.org/2000/svg">'
  + '<path d="M16 0C7.7 0 1 6.7 1 15c0 10 15 25 15 25s15-15 15-25C31 6.7 24.3 0 16 0z" fill="#E60000"/>'
  + '<circle cx="16" cy="15" r="6" fill="#fff"/></svg>'

function load(): Promise<void> {
  if ((window as any).ymaps) {
    return Promise.resolve()
  }

  const key = config.public.yandexMapsKey
  const src = `https://api-maps.yandex.ru/2.1/?${key ? `apikey=${key}&` : ''}lang=${LANGS[locale.value] ?? 'ru_RU'}`

  return new Promise((resolve, reject) => {
    const existing = document.querySelector<HTMLScriptElement>(`script[data-ymaps]`)

    if (existing) {
      existing.addEventListener('load', () => resolve())
      existing.addEventListener('error', () => reject(new Error('ymaps')))

      return
    }

    const script = document.createElement('script')

    script.src = src
    script.async = true
    script.dataset.ymaps = ''
    script.addEventListener('load', () => resolve())
    script.addEventListener('error', () => reject(new Error('ymaps')))
    document.head.appendChild(script)
  })
}

function balloon(point: Office): string {
  const tag = point.type === 'dealer' ? t('offices.dealer') : t('offices.office')

  return `<div class="map__balloon">`
    + `<span class="map__popup-tag${point.type === 'dealer' ? ' map__popup-tag_dealer' : ''}">${tag}</span>`
    + `<h3 class="map__popup-title">${officeTitle(point)}</h3>`
    + `<p class="map__popup-text">${point.address}</p>`
    + `</div>`
}

function draw(): void {
  const ymaps = (window as any).ymaps

  markers.forEach(marker => map.geoObjects.remove(marker))
  markers.clear()

  props.points.forEach(point => {
    if (point.lat === null || point.lng === null) {
      return
    }

    const marker = new ymaps.Placemark([point.lat, point.lng], { balloonContent: balloon(point) }, {
      iconLayout: ymaps.templateLayoutFactory.createClass(`<div class="map-pin">${PIN}</div>`),
      iconShape: { type: 'Rectangle', coordinates: [[-16, -40], [16, 0]] },
    })

    marker.events.add('click', () => emit('select', point.id))
    markers.set(point.id, marker)
    map.geoObjects.add(marker)
  })
}

function zoom(step: number): void {
  map?.setZoom(map.getZoom() + step, { duration: 200 })
}

onMounted(async () => {
  try {
    await load()
  }
  catch {
    failed.value = true

    return
  }

  const ymaps = (window as any).ymaps

  ymaps.ready(() => {
    if (!canvas.value) {
      return
    }

    map = new ymaps.Map(canvas.value, { center: [41.6, 64.5], zoom: 6, controls: [] }, {
      suppressMapOpenBlock: true,
      balloonPanelMaxMapArea: 400 * 400,
    })
    map.behaviors.disable('scrollZoom')
    draw()
  })
})

onBeforeUnmount(() => {
  map?.destroy()
  map = null
})

watch(() => props.points, () => map && draw())

watch(() => props.active, id => {
  const marker = id === null ? null : markers.get(id)

  if (!map || !marker) {
    return
  }

  const point = props.points.find(item => item.id === id)

  if (point?.lat != null && point.lng != null) {
    map.setCenter([point.lat, point.lng], 13, { duration: 800 })
  }

  marker.balloon.open()
})
</script>

<template>
  <div class="map">
    <div v-if="failed" class="map__canvas">
      <p class="map__fallback">{{ t('offices.map_unavailable') }}</p>
    </div>
    <div v-else ref="canvas" class="map__canvas" role="application" :aria-label="t('offices.map_label')"></div>
    <div class="map__zoom">
      <button class="map__zoom-btn" type="button" :aria-label="t('offices.zoom_in')" @click="zoom(1)">+</button>
      <button class="map__zoom-btn" type="button" :aria-label="t('offices.zoom_out')" @click="zoom(-1)">−</button>
    </div>
  </div>
</template>
