<script setup lang="ts">
import type { CoverageLayer } from '~/composables/useCoverage'

const props = defineProps<{ layers: CoverageLayer[], active: string, center: [number, number] | null }>()

const t = useT()
const shapesOf = useCoverageShapes()
const { locale } = useI18n()
const config = useRuntimeConfig()

const canvas = useTemplateRef('canvas')
const failed = ref(false)

let map: any = null
let drawing = 0
let pin: any = null
const drawn: any[] = []

const LANGS: Record<string, string> = { ru: 'ru_RU', uz: 'uz_UZ', en: 'en_US' }

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

/**
 * GeoJSON orders a pair as longitude first; the map wants latitude first.
 */
function flip(ring: number[][]): number[][] {
  return ring.map(([lng, lat]) => [lat, lng])
}

async function draw(): Promise<void> {
  const token = ++drawing

  if (!map) {
    return
  }

  for (const shape of drawn.splice(0)) {
    map.geoObjects.remove(shape)
  }

  const layer = props.layers.find(item => item.key === props.active)

  if (!layer) {
    return
  }

  const shapes = await shapesOf(layer.key).catch(() => null)

  if (!shapes || !map || token !== drawing) {
    return
  }

  const ymaps = (window as any).ymaps
  const style = {
    strokeColor: layer.color,
    strokeWidth: 1,
    fillColor: layer.color,
    fillOpacity: 0.28,
    fillRule: 'evenOdd',
  }

  /**
   * The planning export is one MultiPolygon of tens of thousands of disjoint
   * patches. They are drawn even-odd in batches: one geo-object per batch
   * keeps the map responsive where one object per patch would bury it.
   */
  const BATCH = 2000

  for (const feature of shapes.features) {
    const geometry = feature.geometry

    if (geometry.type === 'LineString') {
      const line = new ymaps.Polyline(flip(geometry.coordinates as number[][]), { hintContent: layer.name }, style)

      map.geoObjects.add(line)
      drawn.push(line)

      continue
    }

    const contours = geometry.type === 'MultiPolygon'
      ? (geometry.coordinates as number[][][][]).flat()
      : (geometry.coordinates as number[][][])

    for (let index = 0; index < contours.length; index += BATCH) {
      const shape = new ymaps.Polygon(
        contours.slice(index, index + BATCH).map(flip),
        { hintContent: layer.name },
        style,
      )

      map.geoObjects.add(shape)
      drawn.push(shape)
    }
  }
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

    map = new ymaps.Map(canvas.value, {
      center: [41.6, 64.5],
      zoom: 6,
      controls: ['fullscreenControl'],
    }, {
      suppressMapOpenBlock: true,
    })

    map.behaviors.disable('scrollZoom')

    draw()
  })
})

/**
 * The wheel scrolls the page until Ctrl joins in — then it zooms the map,
 * the way every embedded map behaves.
 */
function onModifier(event: KeyboardEvent): void {
  if (event.key !== 'Control' || !map) {
    return
  }

  map.behaviors[event.type === 'keydown' ? 'enable' : 'disable']('scrollZoom')
}

function releaseScrollZoom(): void {
  map?.behaviors.disable('scrollZoom')
}

onMounted(() => {
  window.addEventListener('keydown', onModifier)
  window.addEventListener('keyup', onModifier)
  window.addEventListener('blur', releaseScrollZoom)
})

onBeforeUnmount(() => {
  window.removeEventListener('keydown', onModifier)
  window.removeEventListener('keyup', onModifier)
  window.removeEventListener('blur', releaseScrollZoom)
  map?.destroy()
  map = null
})

watch(() => [props.layers, props.active], () => draw())

watch(() => props.center, (coords) => {
  if (map && coords) {
    map.setCenter(coords, 10, { duration: 800 })
  }
})

function zoom(step: number): void {
  map?.setZoom(map.getZoom() + step, { duration: 200 })
}

/**
 * Drops a pin on the geocoded address and flies to it, so the caller only has
 * to know whether the address resolved at all.
 */
async function find(query: string): Promise<boolean> {
  const ymaps = (window as any).ymaps

  if (!map || !ymaps) {
    return false
  }

  const found = await ymaps.geocode(`Uzbekistan, ${query}`, { results: 1 })
    .then((result: any) => result.geoObjects.get(0))
    .catch(() => null)

  if (!found) {
    return false
  }

  const coords = found.geometry.getCoordinates()

  if (pin) {
    map.geoObjects.remove(pin)
  }

  pin = new ymaps.Placemark(coords, { hintContent: found.getAddressLine() }, { preset: 'islands#redDotIcon' })
  map.geoObjects.add(pin)
  map.setCenter(coords, 14, { duration: 800 })

  return true
}

defineExpose({ find })
</script>

<template>
  <div class="map map_coverage">
    <p v-if="failed" class="map__fallback">{{ t('coverage.map_unavailable') }}</p>
    <div v-else ref="canvas" class="map__canvas" role="application" :aria-label="t('coverage.map_label')"></div>

    <div v-if="!failed" class="map__zoom">
      <button class="map__zoom-btn" type="button" :aria-label="t('coverage.zoom_in')" @click="zoom(1)">+</button>
      <button class="map__zoom-btn" type="button" :aria-label="t('coverage.zoom_out')" @click="zoom(-1)">−</button>
    </div>
  </div>
</template>
