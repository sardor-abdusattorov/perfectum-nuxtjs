---
globs: config/app.php, app/Support/helpers.php, app/Providers/AppServiceProvider.php, app/Http/Middleware/SetLocale.php
---

# Locales

## Three lists, not one

`config/app.php` holds three, and they answer three different questions. They
were one list until an operator wanted English content fields and found the
list capped by the two languages the admin panel is translated into.

| Config | Helper | Means | Bounded by |
| --- | --- | --- | --- |
| `app.locales` | `app_locales()` | content: field tabs, JSON keys, the locale a request may name | nothing |
| `app.panel_locales` | `panel_locales()` | the admin interface | a `lang/<code>` directory existing |
| `app.site_locales` | `site_locales()` | what the site serves under a URL prefix | the frontend being ready |

`panel_locales`, `site_locales` and `required_locales` stay subsets of
`app.locales`, and `app.locale` belongs to all three. A test asserts it.

Content is allowed to run ahead of the site, and that is the point: editors
fill a language for weeks before any `/en/` page exists. Nothing on the site
changes when a content locale is added.

## Adding a language

**Content only** — one line: append the code to `app.locales`. Leave
`required_locales` alone, or every form with a translatable field stops saving
until the new language is filled. Check `app.label.<code>` exists in
`lang/ru/app.php` and `lang/uz/app.php`: `TranslatableTabs::getLocales()`
indexes the label map with no `??` and throws on every translatable form
without it. Deploy needs `config:cache` and nothing else — the translations
live in JSON columns, so there is no migration.

**The admin interface** — translate all five files into `lang/<code>/`
(`app`, `auth`, `pagination`, `passwords`, `validation`), then add the code to
`panel_locales`. Half a translation gives half a panel: Filament and Shield
carry their own English, our `app.*` strings would fall back to Russian.

**The site** — only after the content locale exists and is filled. Add the code
to `site_locales`, to `i18n.locales` in `frontend/nuxt.config.ts`, to `LOCALES`
in `frontend/server/routes/sitemap.xml.ts`, and to the prefix patterns in
`frontend/server/middleware/redirects.ts` and
`frontend/app/composables/usePreview.ts` — a missed pattern silently kills the
redirects and the preview `noindex` on the new language. The frontend has no
translation files: its copy comes from `SiteTranslation` through `useT()`.

## Two traps that cost an afternoon each

**Guessing the locale.** `SetLocale` resolves a named locale against the
content list, so a client may ask for content the site does not publish. The
`Accept-Language` guess runs over `site_locales()` instead. Guessing over the
content list sends every request without an `X-Locale` header to whatever the
browser prefers — and Symfony's own test client sends `en-us,en;q=0.5`, so
adding English silently moved the whole suite, the mobile app and every crawler
onto a language with no pages.

**An untouched editor is not empty.** It dehydrates to `<p></p>`, which spatie
counts as a filled translation and stops falling back. Opening a record and
pressing Save without visiting the new tab would leave that language with a
Russian title over an empty body. `RichContentStateCast::get()` maps the empty
document back to `null`; do not replace that check with `strip_tags()`, since a
body that is one image strips to nothing as well.

## Search does not fall back

`ListsRecords::searchedIn()` builds `title-><locale>` with no fallback: a record
written only in Russian is readable in another locale but not findable there.
Harmless while the language is content-only; fix it before the site serves that
language.
