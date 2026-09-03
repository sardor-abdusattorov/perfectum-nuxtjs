import type { H3Event } from 'h3'

const PACKAGE = 'uz.rwc.perfectum'
const SCHEME = 'perfectum'
const PLAY = `https://play.google.com/store/apps/details?id=${PACKAGE}`
const APPSTORE = 'https://apps.apple.com/uz/app/perfectum/id6771314299'

function deepPath(event: H3Event): string {
  const [path = '', query = ''] = event.path.split('?')
  const rest = path.replace(/^\/app\/?/, '').replace(/^\/+/, '')
  const safe = `${rest}${query ? `?${query}` : ''}`.replace(/[^\w\-./?&=%]/g, '')

  return JSON.stringify(safe).replace(/</g, '\\u003C')
}

export function sendAppLink(event: H3Event) {
  setHeader(event, 'content-type', 'text/html; charset=utf-8')
  setHeader(event, 'cache-control', 'no-store')
  setHeader(event, 'x-robots-tag', 'noindex, nofollow')

  return `<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Perfectum</title>
<style>
:root { --red: #E60000; --line: rgba(0,0,0,.1) }
* { box-sizing: border-box }
body { margin: 0; min-height: 100dvh; display: grid; place-items: center;
  font: 16px/1.5 system-ui, -apple-system, sans-serif; color: #000; background: #fff }
main { width: 100%; max-width: 22rem; padding: 2rem 1.5rem; text-align: center }
img { display: block; margin: 0 auto 2rem; height: 2rem; width: auto }
p { margin: 0 0 2rem; color: rgba(0,0,0,.56) }
a { display: block; padding: .875rem 1rem; margin-top: .75rem; border-radius: 80px;
  border: 1px solid var(--line); color: inherit; text-decoration: none; font-weight: 500 }
a.primary { background: var(--red); border-color: var(--red); color: #fff }
</style>
</head>
<body>
<main>
  <img src="/images/logo.svg" alt="Perfectum">
  <p id="status">Открываем приложение…</p>
  <a class="primary" href="${PLAY}">Google Play</a>
  <a href="${APPSTORE}">App Store</a>
</main>
<script>
(function () {
  var deep = ${deepPath(event)}
  var ua = navigator.userAgent
  var android = /Android/i.test(ua)
  var ios = /iPhone|iPad|iPod/i.test(ua)
    || (/Macintosh/.test(ua) && navigator.maxTouchPoints > 1)

  if (android) {
    location.replace('intent://' + deep + '#Intent;scheme=${SCHEME};package=${PACKAGE}'
      + ';S.browser_fallback_url=' + encodeURIComponent('${PLAY}') + ';end')
    return
  }

  if (ios) {
    var store = setTimeout(function () { location.replace('${APPSTORE}') }, 1500)

    addEventListener('pagehide', function () { clearTimeout(store) })
    addEventListener('visibilitychange', function () {
      if (document.visibilityState === 'hidden') clearTimeout(store)
    })

    location.replace('${SCHEME}://' + deep)
    return
  }

  document.getElementById('status').textContent = 'Скачайте приложение Perfectum'
})()
</script>
</body>
</html>`
}
