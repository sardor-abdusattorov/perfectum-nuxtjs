const STOPS = [0, 10, 50, 80, 100, 250, 500, 750, 1000]

const START = -135.5
const END = 135.5

const STEP = (END - START) / (STOPS.length - 1)

const PIVOT = { x: 90.9324, y: 97.5113 }
const CENTRE = { x: 98, y: 98 }
const TIP = { x: 150.849, y: 156.018 }

const GAUGE_MAX = STOPS[STOPS.length - 1]!

function degrees(x: number, y: number): number {
  return Math.atan2(x, -y) * 180 / Math.PI
}

export function gaugeAngle(value: number): number {
  if (!(value > 0)) {
    return START
  }

  if (value >= GAUGE_MAX) {
    return END
  }

  const next = STOPS.findIndex(stop => stop > value)
  const low = STOPS[next - 1]!
  const high = STOPS[next]!

  return START + (next - 1 + (value - low) / (high - low)) * STEP
}

export function gaugeValue(angle: number): number {
  const position = Math.min(Math.max((angle - START) / STEP, 0), STOPS.length - 1)
  const index = Math.min(Math.floor(position), STOPS.length - 2)
  const low = STOPS[index]!

  return low + (position - index) * (STOPS[index + 1]! - low)
}

export function gaugeNeedle(angle: number): number {
  const radians = angle * Math.PI / 180
  const ux = Math.sin(radians)
  const uy = -Math.cos(radians)

  const dx = PIVOT.x - CENTRE.x
  const dy = PIVOT.y - CENTRE.y
  const vx = TIP.x - PIVOT.x
  const vy = TIP.y - PIVOT.y

  const projection = ux * dx + uy * dy
  const reach = Math.sqrt(Math.max(
    0,
    projection ** 2 - (dx ** 2 + dy ** 2) + (vx ** 2 + vy ** 2),
  ))
  const length = projection + reach

  return degrees(length * ux - dx, length * uy - dy) - degrees(vx, vy)
}

export function gaugeLabel(value: number): string {
  return String(Math.round(value)).replace(/\B(?=(\d{3})+(?!\d))/g, ' ')
}

export function gaugeReading(raw: string | number | null | undefined): number | null {
  if (raw === null || raw === undefined || raw === '') {
    return null
  }

  const value = Number(String(raw).replace(/[^\d.]/g, ''))

  return Number.isFinite(value) ? value : null
}
