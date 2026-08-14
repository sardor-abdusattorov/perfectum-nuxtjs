<script setup lang="ts">
import type { Office } from '~/composables/useOffices'

const props = defineProps<{ points: Office[], userCoords: [number, number] | null }>()
const emit = defineEmits<{ select: [id: number] }>()

const t = useT()
const { locale } = useI18n()
const config = useRuntimeConfig()

const canvas = useTemplateRef('canvas')
const failed = ref(false)

let map: any = null
let clusterer: any = null
let userPlacemark: any = null
const markers = new Map<number, any>()

const LANGS: Record<string, string> = { ru: 'ru_RU', uz: 'uz_UZ', en: 'en_US' }

function escapeHtml(value: string): string {
  return value.replace(/[&<>"']/g, char => (
    { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[char] as string
  ))
}

function load(): Promise<void> {
  if ((window as any).ymaps) {
    return Promise.resolve()
  }

  const key = config.public.yandexMapsKey
  const src = `https://api-maps.yandex.ru/2.1/?${key ? `apikey=${key}&` : ''}lang=${LANGS[locale.value] ?? 'ru_RU'}`

  return new Promise((resolve, reject) => {
    const existing = document.querySelector<HTMLScriptElement>('script[data-ymaps]')

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

function placemark(point: Office): any {
  const ymaps = (window as any).ymaps
  const title = escapeHtml(officeTitle(point))

  const dealer = point.type === 'dealer'

  /**
   * The API's own chrome is stripped in CSS so the card can be ours, which
   * means the whole balloon has to come out of one slot — a header and a body
   * would be laid out by the API between them.
   */
  const marker = new ymaps.Placemark([point.lat, point.lng], {
    balloonContent:
      `<div class="map__balloon">`
      + `<span class="map__popup-tag${dealer ? ' map__popup-tag_dealer' : ''}">${escapeHtml(t(dealer ? 'offices.dealer' : 'offices.office'))}</span>`
      + `<h3 class="map__popup-title">${title}</h3>`
      + `<p class="map__popup-text">${escapeHtml(point.address)}</p>`
      + `<a class="map__popup-btn" target="_blank" rel="noopener noreferrer" href="https://yandex.ru/maps/?rtext=~${point.lat},${point.lng}&rtt=auto">${escapeHtml(t('offices.route'))}</a>`
      + `</div>`,
    hintContent: title,
  }, {
    preset: point.type === 'dealer' ? 'islands#blueCircleDotIcon' : 'islands#redIcon',
  })

  marker.events.add('click', () => emit('select', point.id))

  return marker
}

function draw(): void {
  if (!clusterer) {
    return
  }

  clusterer.removeAll()
  markers.clear()

  for (const point of props.points) {
    if (point.lat === null || point.lng === null) {
      continue
    }

    markers.set(point.id, placemark(point))
  }

  clusterer.add([...markers.values()])
}

/**
 * The balloon opens even when the point is currently folded into a cluster,
 * the way the live site does it.
 */
function openBalloon(id: number): void {
  const marker = markers.get(id)

  if (!marker) {
    return
  }

  const state = clusterer.getObjectState(marker)

  if (state.isClustered) {
    state.cluster.state.set('activeObject', marker)
    clusterer.balloon.open(state.cluster)
  }
  else if (state.isShown) {
    marker.balloon.open()
  }
}

function focus(id: number): void {
  const point = props.points.find(item => item.id === id)

  if (!map || !point || point.lat === null || point.lng === null) {
    return
  }

  map.setCenter([point.lat, point.lng], 14, { duration: 400 })
  canvas.value?.scrollIntoView({ behavior: 'smooth', block: 'center' })
  setTimeout(() => openBalloon(id), 450)
}

defineExpose({ focus })

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

    map = new ymaps.Map(canvas.value, {
      center: [41.31, 64.5],
      zoom: 6,
      controls: ['zoomControl', 'geolocationControl', 'typeSelector', 'fullscreenControl'],
    }, {
      suppressMapOpenBlock: true,
    })

    clusterer = new ymaps.Clusterer({
      preset: 'islands#invertedRedClusterIcons',
      groupByCoordinates: false,
      gridSize: 64,
    })
    map.geoObjects.add(clusterer)

    draw()

    if (props.userCoords) {
      showUser(props.userCoords)
    }
  })
})

onBeforeUnmount(() => {
  map?.destroy()
  map = null
  clusterer = null
})

watch(() => props.points, () => draw())

function showUser(coords: [number, number]): void {
  const ymaps = (window as any).ymaps

  if (userPlacemark) {
    map.geoObjects.remove(userPlacemark)
  }

  userPlacemark = new ymaps.Placemark(coords, { hintContent: t('offices.you_here') }, { preset: 'islands#geolocationIcon' })
  map.geoObjects.add(userPlacemark)
  map.setCenter(coords, 11, { duration: 400 })
}

watch(() => props.userCoords, (coords) => {
  if (map && coords) {
    showUser(coords)
  }
})
</script>

<template>
  <div class="map">
    <div v-if="failed" class="map__canvas">
      <p class="map__fallback">{{ t('offices.map_unavailable') }}</p>
    </div>
    <div v-else ref="canvas" class="map__canvas" role="application" :aria-label="t('offices.map_label')"></div>
  </div>
</template>
