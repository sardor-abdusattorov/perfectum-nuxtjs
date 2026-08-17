/**
 * The hero speedometer's ticks are painted into the artwork, so the scale is
 * the artwork's: nine stops spread evenly around the arc, each interval
 * covering a different span of speed. A value reads the way a speedtest dial
 * does — find the pair of stops it falls between, then interpolate the angle.
 */
const STOPS = [0, 10, 50, 80, 100, 250, 500, 750, 1000]

/** where the first and last stop sit, measured clockwise from twelve o'clock */
const START = -135.5
const END = 135.5

const STEP = (END - START) / (STOPS.length - 1)

/**
 * The needle turns about its own base, and the artwork put that base a little
 * up and left of the dial's centre — so the rotation that aims the tip at a
 * tick is never quite the tick's own angle.
 */
const PIVOT = { x: 90.9324, y: 97.5113 }
const CENTRE = { x: 98, y: 98 }
const TIP = { x: 150.849, y: 156.018 }

const GAUGE_MAX = STOPS[STOPS.length - 1]!

function degrees(x: number, y: number): number {
  return Math.atan2(x, -y) * 180 / Math.PI
}

/** speed → the angle its tick sits at */
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

/** the angle back to the speed it reads, so the two never drift apart */
export function gaugeValue(angle: number): number {
  const position = Math.min(Math.max((angle - START) / STEP, 0), STOPS.length - 1)
  const index = Math.min(Math.floor(position), STOPS.length - 2)
  const low = STOPS[index]!

  return low + (position - index) * (STOPS[index + 1]! - low)
}

/**
 * The rotation to put the needle's tip on a given angle: the tip has to land
 * where the line out of the dial's centre crosses the circle the pivot swings
 * it around, which is one quadratic rather than a guess.
 */
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

/** the readout wears thin spaces between thousands, as the design draws it */
export function gaugeLabel(value: number): string {
  return String(Math.round(value)).replace(/\B(?=(\d{3})+(?!\d))/g, ' ')
}

/** the stored value may be a plain number or the "1 000" the seeder wrote */
export function gaugeReading(raw: string | number | null | undefined): number | null {
  if (raw === null || raw === undefined || raw === '') {
    return null
  }

  const value = Number(String(raw).replace(/[^\d.]/g, ''))

  return Number.isFinite(value) ? value : null
}
