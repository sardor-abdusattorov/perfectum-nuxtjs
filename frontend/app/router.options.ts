import type { RouterConfig } from '@nuxt/schema'

type Target = { left: number, top: number, behavior: ScrollBehavior }
  | { el: string, top: number, behavior: ScrollBehavior }

export default <RouterConfig>{
  scrollBehavior(to, from, savedPosition) {
    const samePage = to.path === from.path

    if (samePage && !to.hash) {
      return false
    }

    const target: Target = to.hash
      ? { el: to.hash, top: 120, behavior: 'smooth' }
      : savedPosition
        ? { ...savedPosition, behavior: 'instant' }
        : { left: 0, top: 0, behavior: 'instant' }

    if (samePage) {
      return target
    }

    const nuxtApp = useNuxtApp()

    return new Promise(resolve => {
      nuxtApp.hooks.hookOnce('page:finish', () => {
        requestAnimationFrame(() => resolve(target))
      })
    })
  },
}
