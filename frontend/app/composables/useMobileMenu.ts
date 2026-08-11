export function useMobileMenu() {
  const open = useState('menu:open', () => false)
  const route = useRoute()

  watch(() => route.fullPath, () => {
    open.value = false
  })

  watch(open, value => {
    if (import.meta.client) {
      document.body.classList.toggle('overflow__hidden', value)
    }
  })

  onScopeDispose(() => {
    if (import.meta.client) {
      document.body.classList.remove('overflow__hidden')
    }
  })

  return {
    open,
    toggle: () => { open.value = !open.value },
    close: () => { open.value = false },
  }
}
