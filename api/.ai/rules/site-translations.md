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

## Contacts live in `site_settings`, translations hold only the labels

An address, a phone, an email or a store URL is one value for all three
locales, so it belongs in `site_settings` where it is edited once —
`email_info`, `email_hotline`, `phone_primary`, `telegram_url`,
`app_store_url`, `google_play_url`. The translation keys next to them carry
only the wording (`footer.email_info_note`).

Putting the address inside the translation string means changing an email
in three places and losing the `mailto:`/`tel:` link.

## Every `t()` call takes a fallback

A missing translation renders its own key on the page (`footer.address`),
which is worse than English text. Pass the Russian wording as the second
argument at every call site.
