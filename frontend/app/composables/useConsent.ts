import type { ApiResponse, Metrics } from '~/types/api'

const COOKIE_NAME = 'cookie_consent'
const YEAR = 60 * 60 * 24 * 365

export function useConsent() {
  const consent = useCookie<'accepted' | null>(COOKIE_NAME, {
    maxAge: YEAR,
    sameSite: 'lax',
    path: '/',
  })

  const settings = useSiteSettings()
  const injected = useState('consent:injected', () => false)
  const { $api } = useNuxtApp()

  const available = computed(() => settings.value?.metrics.enabled === true)
  const visible = computed(() => available.value && consent.value !== 'accepted')

  async function inject(): Promise<void> {
    if (injected.value || import.meta.server) {
      return
    }

    injected.value = true

    const { data } = await $api<ApiResponse<Metrics>>('/metrics')

    for (const code of Object.values(data)) {
      run(code)
    }
  }

  function accept(): void {
    consent.value = 'accepted'

    inject()
  }

  onMounted(() => {
    if (available.value && consent.value === 'accepted') {
      inject()
    }
  })

  return { visible, accept }
}

function run(html: string): void {
  const template = document.createElement('template')
  template.innerHTML = html

  for (const source of Array.from(template.content.querySelectorAll('script'))) {
    const script = document.createElement('script')

    for (const attribute of Array.from(source.attributes)) {
      script.setAttribute(attribute.name, attribute.value)
    }

    script.textContent = source.textContent
    document.head.appendChild(script)
  }

  for (const node of Array.from(template.content.querySelectorAll('noscript'))) {
    document.body.appendChild(node.cloneNode(true))
  }
}
