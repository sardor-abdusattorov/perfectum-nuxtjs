<script setup lang="ts">
import type { CoverageLayer } from '~/composables/useCoverage'

const props = defineProps<{
  layers: CoverageLayer[]
  active: string
  center: [number, number] | null
  stage?: HTMLElement | null
}>()

const emit = defineEmits<{ failed: [boolean] }>()

const t = useT()
const shapesOf = useCoverageShapes()
const loadMaps = useYandexMaps()

const root = useTemplateRef('root')
const canvas = useTemplateRef('canvas')
const failed = ref(false)
const expanded = ref(false)
const hint = ref('')

let map: any = null
let drawing = 0
let pin: any = null
let me: any = null
let saying: ReturnType<typeof setTimeout> | null = null
const drawn: any[] = []

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

  emit('failed', shapes === null)

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
  const ymaps = await loadMaps().catch(() => null)

  if (!ymaps || !canvas.value) {
    failed.value = true

    return
  }

  map = new ymaps.Map(canvas.value, {
    center: props.center ?? [41.6, 64.5],
    zoom: props.center ? 10 : 6,
    controls: [],
  }, {
    suppressMapOpenBlock: true,
  })

  map.behaviors.disable('scrollZoom')

  draw()
})

function onModifier(event: KeyboardEvent): void {
  if (event.key !== 'Control' || !map) {
    return
  }

  map.behaviors[event.type === 'keydown' ? 'enable' : 'disable']('scrollZoom')
}

function releaseScrollZoom(): void {
  map?.behaviors.disable('scrollZoom')
}

function onFullscreenChange(): void {
  expanded.value = document.fullscreenElement !== null

  requestAnimationFrame(() => map?.container.fitToViewport())
}

async function toggleFullscreen(): Promise<void> {
  const target = props.stage ?? root.value

  if (!target) {
    return
  }

  await (document.fullscreenElement
    ? document.exitFullscreen()
    : target.requestFullscreen()).catch(() => {})
}

function say(text: string): void {
  hint.value = text

  if (saying) {
    clearTimeout(saying)
  }

  saying = text ? setTimeout(() => (hint.value = ''), 6000) : null
}

function locate(): void {
  const ymaps = (window as any).ymaps

  if (!map || !ymaps || !navigator.geolocation) {
    say(t('coverage.locate_failed', 'Не удалось определить местоположение. Разрешите доступ к геолокации.'))

    return
  }

  say(t('coverage.locating', 'Определяем ваше местоположение…'))

  navigator.geolocation.getCurrentPosition(
    (position) => {
      const coords = [position.coords.latitude, position.coords.longitude]

      if (me) {
        map.geoObjects.remove(me)
      }

      me = new ymaps.Placemark(
        coords,
        { hintContent: t('coverage.you_here', 'Вы здесь') },
        { preset: 'islands#geolocationIcon' },
      )

      map.geoObjects.add(me)
      map.setCenter(coords, 13, { duration: 800 })
      say('')
    },
    () => say(t('coverage.locate_failed', 'Не удалось определить местоположение. Разрешите доступ к геолокации.')),
    { enableHighAccuracy: true, timeout: 8000 },
  )
}

onMounted(() => {
  window.addEventListener('keydown', onModifier)
  window.addEventListener('keyup', onModifier)
  window.addEventListener('blur', releaseScrollZoom)
  document.addEventListener('fullscreenchange', onFullscreenChange)
})

onBeforeUnmount(() => {
  window.removeEventListener('keydown', onModifier)
  window.removeEventListener('keyup', onModifier)
  window.removeEventListener('blur', releaseScrollZoom)
  document.removeEventListener('fullscreenchange', onFullscreenChange)

  if (saying) {
    clearTimeout(saying)
  }

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
  <div ref="root" class="map map_coverage">
    <p v-if="failed" class="map__fallback">{{ t('coverage.map_unavailable') }}</p>
    <div v-else ref="canvas" class="map__canvas" role="application" :aria-label="t('coverage.map_label')"></div>

    <template v-if="!failed">
      <div class="map__zoom">
        <button class="map__zoom-btn" type="button" :aria-label="t('coverage.zoom_in')" @click="zoom(1)">+</button>
        <button class="map__zoom-btn" type="button" :aria-label="t('coverage.zoom_out')" @click="zoom(-1)">−</button>
        <button class="map__zoom-btn" type="button"
            :aria-label="t('coverage.locate', 'Моё местоположение')" @click="locate()">
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.8" />
            <path d="M12 2v3M12 19v3M2 12h3M19 12h3" stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" />
          </svg>
        </button>
      </div>

      <p v-if="hint" class="map__hint" role="status">{{ hint }}</p>

      <button
        class="map__full"
        type="button"
        :aria-pressed="expanded"
        :aria-label="t(expanded ? 'coverage.exit_fullscreen' : 'coverage.fullscreen', expanded ? 'Выйти из полноэкранного режима' : 'Во весь экран')"
        @click="toggleFullscreen()"
      >
        <svg v-if="expanded" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M9 4v5H4M15 4v5h5M9 20v-5H4M15 20v-5h5" stroke="currentColor" stroke-width="1.8"
              stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <svg v-else viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M4 9V4h5M20 9V4h-5M4 15v5h5M20 15v5h-5" stroke="currentColor" stroke-width="1.8"
              stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </button>
    </template>
  </div>
</template>
