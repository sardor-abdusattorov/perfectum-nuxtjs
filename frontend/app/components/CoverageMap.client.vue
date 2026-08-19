<script setup lang="ts">
import type { CoverageLayer } from '~/composables/useCoverage'
import L from 'leaflet'

const props = defineProps<{ layers: CoverageLayer[], active: string, center: [number, number] | null }>()
const emit = defineEmits<{ failed: [boolean] }>()

const t = useT()
const shapesOf = useCoverageShapes()

const canvas = useTemplateRef('canvas')

/**
 * The old site drew the coverage with Leaflet over free CARTO tiles, and the
 * outlines are the whole point of the page: a map that needs a paid key is a
 * map that shows nothing the day the key lapses.
 */
const TILES = 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png'
const ATTRIBUTION = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>, &copy; <a href="https://carto.com/attributions">CARTO</a>'
const UZBEKISTAN: L.LatLngTuple = [41.6, 64.5]

let map: L.Map | null = null
let shapes: L.GeoJSON | null = null
let pin: L.CircleMarker | null = null
let drawing = 0

/**
 * The export is one MultiPolygon of tens of thousands of disjoint patches.
 * Canvas draws them as one surface, where a path per patch would bury the
 * browser, and nothing on the layer is clickable anyway.
 */
async function draw(): Promise<void> {
  const token = ++drawing

  if (!map) {
    return
  }

  shapes?.remove()
  shapes = null

  const layer = props.layers.find(item => item.key === props.active)

  if (!layer) {
    return
  }

  const collection = await shapesOf(layer.key).catch(() => null)

  emit('failed', collection === null)

  if (!collection || !map || token !== drawing) {
    return
  }

  shapes = L.geoJSON(collection as unknown as GeoJSON.GeoJsonObject, {
    renderer: L.canvas({ padding: 0.5 }),
    interactive: false,
    style: {
      color: 'transparent',
      weight: 1,
      fillColor: layer.color,
      fillOpacity: 0.3,
    },
  }).addTo(map)

  const bounds = shapes.getBounds()

  if (bounds.isValid()) {
    map.fitBounds(bounds)
  }
}

/**
 * A client-only component renders its markup a tick after it mounts, so the
 * map is built when the canvas actually appears rather than on mount.
 */
watch(canvas, (node) => {
  if (!node || map) {
    return
  }

  map = L.map(node, {
    center: props.center ?? UZBEKISTAN,
    zoom: props.center ? 10 : 6,
    zoomControl: false,
    scrollWheelZoom: false,
  })

  L.tileLayer(TILES, { attribution: ATTRIBUTION, maxZoom: 19 }).addTo(map)

  draw()
}, { immediate: true })

/**
 * The wheel scrolls the page until Ctrl joins in — then it zooms the map,
 * the way every embedded map behaves.
 */
function onModifier(event: KeyboardEvent): void {
  if (event.key !== 'Control' || !map) {
    return
  }

  map.scrollWheelZoom[event.type === 'keydown' ? 'enable' : 'disable']()
}

function releaseScrollZoom(): void {
  map?.scrollWheelZoom.disable()
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
  map?.remove()
  map = null
  shapes = null
  pin = null
})

watch(() => [props.layers, props.active], () => draw())

watch(() => props.center, (coords) => {
  if (map && coords) {
    map.flyTo(coords, 10, { duration: 0.8 })
  }
})

function zoom(step: number): void {
  if (step > 0) {
    map?.zoomIn()
  }
  else {
    map?.zoomOut()
  }
}

interface Place {
  lat: string
  lon: string
  display_name: string
}

/**
 * Drops a pin on the geocoded address and flies to it, so the caller only has
 * to know whether the address resolved at all.
 */
async function find(query: string): Promise<boolean> {
  if (!map) {
    return false
  }

  const found = await $fetch<Place[]>('https://nominatim.openstreetmap.org/search', {
    query: { format: 'jsonv2', limit: 1, countrycodes: 'uz', q: query },
  }).catch(() => null)

  const place = found?.[0]

  if (!place) {
    return false
  }

  const coords: L.LatLngTuple = [Number(place.lat), Number(place.lon)]

  pin?.remove()
  pin = L.circleMarker(coords, {
    radius: 8,
    color: '#ffffff',
    weight: 2,
    fillColor: '#e60000',
    fillOpacity: 1,
  })
    .bindPopup(place.display_name)
    .addTo(map)

  map.flyTo(coords, 15, { duration: 0.8 })
  pin.openPopup()

  return true
}

defineExpose({ find })
</script>

<template>
  <div class="map map_coverage">
    <div ref="canvas" class="map__canvas" role="application" :aria-label="t('coverage.map_label')"></div>

    <div class="map__zoom">
      <button class="map__zoom-btn" type="button" :aria-label="t('coverage.zoom_in')" @click="zoom(1)">+</button>
      <button class="map__zoom-btn" type="button" :aria-label="t('coverage.zoom_out')" @click="zoom(-1)">−</button>
    </div>
  </div>
</template>

<style>
@import 'leaflet/dist/leaflet.css';

/* Leaflet numbers its panes in the hundreds; a stacking context around the
   canvas keeps the whole map under the zoom column the page draws itself. */
.map_coverage .leaflet-container {
  z-index: 0;
  font: inherit;
}

/* The reset caps a canvas at the width of its container, and Leaflet's panes
   have none — the coverage layer collapsed to nothing. */
.map_coverage .leaflet-pane canvas {
  max-width: none;
}
</style>
