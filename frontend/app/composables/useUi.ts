export interface TariffModalPayload {
  name: string
  price: string
  period: string
}

export const useMenuOpen = () => useState<boolean>('ui:menu-open', () => false)

export function useTariffModal() {
  const payload = useState<TariffModalPayload | null>('ui:tariff-modal', () => null)

  return {
    payload,
    open: (value: TariffModalPayload) => {
      payload.value = value
    },
    close: () => {
      payload.value = null
    },
  }
}
