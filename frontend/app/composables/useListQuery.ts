interface ListQueryOptions {
  category?: boolean
  search?: boolean
}

/**
 * A filtered list is a place, not a mood: the search, the chosen category and
 * the page live in the address so the view survives a reload, comes back with
 * the browser's back button and can be handed to someone else. The refs are
 * seeded from the address, so the server renders the very list the link asks
 * for instead of the first page of everything.
 *
 * The category travels as its slug — that is what stays readable in an advert
 * and what survives a reseed, which a row id does not.
 */
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

  // while the address is being applied back onto the refs, they must not
  // rewrite it in return
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
