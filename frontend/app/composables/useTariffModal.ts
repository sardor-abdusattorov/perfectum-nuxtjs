import type { TariffSummary } from '~/types/api'

const current = shallowRef<TariffSummary | null>(null)

export function useTariffModal() {
  function open(tariff: TariffSummary): void {
    current.value = tariff
  }

  function close(): void {
    current.value = null
  }

  return { current, open, close }
}
