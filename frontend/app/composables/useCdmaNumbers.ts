import type { ApiResponse } from '~/types/api'

export interface CdmaFilterOption {
  name: string
  value: string
}

export interface CdmaPrice {
  code: number
  price: number
}

export interface CdmaFilters {
  prefixes: CdmaFilterOption[]
  prices: CdmaPrice[]
}

export interface CdmaNumber {
  number: string
  prefix: string
  price: number
  date: string
}

export interface CdmaPayload {
  numbers: CdmaNumber[]
  total: number
  page: number
  totalPages: number
}

export const CDMA_PER_PAGE = 15

/**
 * The gateway lives outside our API and answers slowly, so the page renders
 * first and the filters arrive behind a preloader. The search itself is the
 * same on the support page and on the CDMA one — only the markup differs.
 */
export function useCdmaNumbers() {
  const { $api } = useNuxtApp()

  const filters = ref<CdmaFilters>({ prefixes: [], prices: [] })
  const prefix = ref('')
  const price = ref(-1)
  const number = ref('')
  const numberInvalid = ref(false)

  const data = ref<CdmaPayload | null>(null)
  const initialLoading = ref(true)
  const busy = ref(false)
  const searched = ref(false)

  const numbers = computed(() => data.value?.numbers ?? [])
  const totalPages = computed(() => data.value?.totalPages ?? 1)
  const page = computed(() => data.value?.page ?? 1)

  onMounted(async () => {
    try {
      filters.value = (await $api<ApiResponse<CdmaFilters>>('/cdma-numbers/filters')).data
    }
    catch {
      filters.value = { prefixes: [], prices: [] }
    }
    finally {
      initialLoading.value = false
    }
  })

  async function search(target = 1): Promise<void> {
    numberInvalid.value = !/^\d{1,4}$/.test(number.value)

    if (numberInvalid.value || busy.value) {
      return
    }

    busy.value = true

    try {
      data.value = (await $api<ApiResponse<CdmaPayload>>('/cdma-numbers', {
        method: 'POST',
        body: {
          number: number.value,
          prefix: prefix.value || undefined,
          price: price.value,
          page: target,
        },
      })).data
    }
    catch {
      data.value = null
    }
    finally {
      busy.value = false
      searched.value = true
    }
  }

  /** the gateway takes four digits at most, and only digits */
  function onNumber(event: Event): void {
    const input = event.target as HTMLInputElement

    number.value = input.value.replace(/\D/g, '').slice(0, 4)
    input.value = number.value

    if (number.value) {
      numberInvalid.value = false
    }
  }

  function spaces(value: number): string {
    return String(value).replace(/\B(?=(\d{3})+(?!\d))/g, ' ')
  }

  return {
    filters,
    prefix,
    price,
    number,
    numberInvalid,
    initialLoading,
    busy,
    searched,
    numbers,
    totalPages,
    page,
    search,
    onNumber,
    spaces,
  }
}
