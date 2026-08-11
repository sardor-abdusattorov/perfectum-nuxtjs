---
globs: app/Filament/**, app/Support/Slug.php
---

# Admin forms

## The slug is optional and generated server-side

`SlugInput` is not `->required()`. An empty slug is filled in
`mutateFormDataBeforeCreate` / `mutateFormDataBeforeSave` by the
`GeneratesSlug` trait, which every Create and Edit page of a slugged
resource uses. Marking the field required again silently disables the whole
mechanism — validation rejects the empty value before the mutation runs.

`Slug::make()` picks the first filled translation (English first, it reads
best in a URL), transliterates, truncates to 96 characters and appends
`-2`, `-3`, … until the slug is free. Pass a scope for a table whose unique
index is composite:

```php
protected function slugScope(array $data): array
{
    return ['type' => $data['type'] ?? null];
}
```

A resource whose slug comes from something other than `title` overrides
`slugSource()`.

## Translated fields are required only in ru and uz

Inside `TranslatableTabs`, use `->required(Translated::required())` rather
than `->required()`. The plain call applies to every locale tab and blocks
publishing until all three are written, while everything that reads
translated content already falls back to a filled locale.

The list lives in `config('app.required_locales')` — change it there, not in
the forms.
