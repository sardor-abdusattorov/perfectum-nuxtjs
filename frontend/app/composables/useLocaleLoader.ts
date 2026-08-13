export function useLocaleLoader() {
  const active = useState('locale-loader', () => false)

  function show(): void {
    active.value = true
  }

  return { active, show }
}
