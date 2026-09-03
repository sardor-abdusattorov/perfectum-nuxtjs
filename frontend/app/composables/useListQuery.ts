interface ListQueryOptions {
  category?: boolean
  search?: boolean
}

export function useListQuery(options: ListQueryOptions = {}) {
  const route = useRoute()
  const router = useRouter()

  const read = () => ({
    search: String(route.query.search ?? ''),
    category: String(route.query.category ?? ''),
    page: Math.max(1, Number(route.query.page) || 1),
  })

  const start = read()
  const search = ref(start.search)
  const category = ref(start.category)
  const page = ref(start.page)

  let restoring = false

  watch([search, category], () => {
    if (!restoring) {
      page.value = 1
    }
  })

  watch([search, category, page], () => {
    if (restoring) {
      return
    }

    const query = { ...route.query } as Record<string, string>

    delete query.search
    delete query.category
    delete query.page

    if (options.search !== false && search.value) {
      query.search = search.value
    }

    if (options.category !== false && category.value) {
      query.category = category.value
    }

    if (page.value > 1) {
      query.page = String(page.value)
    }

    router.push({ query })
  })

  watch(() => route.query, () => {
    const current = read()

    if (current.search === search.value && current.category === category.value && current.page === page.value) {
      return
    }

    restoring = true
    search.value = current.search
    category.value = current.category
    page.value = current.page
    nextTick(() => {
      restoring = false
    })
  })

  return { search, category, page }
}
