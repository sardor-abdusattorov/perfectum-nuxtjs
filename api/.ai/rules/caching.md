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
and the matching `clear_*_cache()` helper loops over `config('app.locales')`.
