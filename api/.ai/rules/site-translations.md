---
globs: database/seeders/SiteTranslationSeeder.php, app/Models/SiteTranslation.php, app/Filament/Resources/SiteTranslations/**
---

# Site translations

## The category is always `app`

The admin form writes `category = 'app'` and does not show the field, so
every row created from the panel lands there. Seeders do the same — a row
seeded under any other category is invisible to whoever edits it later.

Grouping lives in the key instead, dotted:

```php
'footer.address' => ['ru' => '…', 'uz' => '…', 'en' => '…'],
```

## The API hands the frontend a flat map

`SiteTranslation::flat()` returns `key => value` for the current locale, so
the frontend looks a line up by its full key:

```ts
t('footer.address')
```

No category level on either side. Adding a line means adding one row with a
dotted key — nothing else changes.
