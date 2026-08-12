const EXTERNAL = /^(https?:)?\/\/|^(mailto|tel|sms):/

export function useUrl(url: MaybeRefOrGetter<string | null | undefined>) {
  const localePath = useLocalePath()

  const raw = computed(() => toValue(url) ?? '')
  const external = computed(() => EXTERNAL.test(raw.value))

  const to = computed(() => {
    if (!raw.value) {
      return null
    }

    if (external.value) {
      return raw.value
    }

    const [path, hash] = raw.value.split('#')

    return localePath(path || '/') + (hash ? `#${hash}` : '')
  })

  return { external, to }
}
