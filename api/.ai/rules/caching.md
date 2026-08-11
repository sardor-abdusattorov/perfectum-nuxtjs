---
globs: app/Models/**, app/Http/Controllers/**, app/Support/helpers.php
---

# Caching

## Never put Eloquent models in the cache

`Cache::remember()` must return plain arrays and scalars. A model, a
relation or an `Eloquent\Collection` survives the first request and comes
back as `__PHP_Incomplete_Class` on the next one, so the endpoint answers
once and then throws a `TypeError` on every following request.

The frontend swallows that 500 and renders its fallbacks, which looks like
"the seeders did not run" rather than an error — it is expensive to track
down. Cache the resolved API resource instead:

```php
Cache::remember(
    Social::cacheKey(),
    Social::CACHE_TTL,
    fn (): array => SocialResource::collection(
        Social::query()->published()->ordered()->get()
    )->resolve(),
);
```

Route model binding resolves a model, so it must not be cached either —
`resolveRouteBinding()` runs the query, and the controller caches the
payload it builds from the model.

## The locale belongs in the cache key

Anything resolved through `HasTranslations` is locale-specific. The key
carries the locale (`pages.{slug}.{locale}`, `menus.{location}.{locale}`)
and the matching `clear_*_cache()` helper loops over `app_locales()` — use
that helper, not a fresh `config('app.locales', ...)` read.

## One entry per table, not one per key

`Settings`, `SiteSettings` and `SiteTranslation` each cache the whole table
as a single array (`Settings::values()`, `SiteSettings::published()`,
`SiteTranslation::published()`); `get()` is an array lookup on top of it.

Two reasons a per-key entry is wrong here. A warm `/site` used to cost
twelve cache round-trips for tables holding a handful of rows. And
`Cache::remember()` treats `null` as a miss, so a key that is simply unset
re-queried the database and rewrote the entry on every single request,
forever. An array of keys is never null, so it caches correctly.

Invalidation follows: the observers forget the one collection key rather
than walking the table to forget each key it might have written.
