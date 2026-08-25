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

`fillSlug()` reads `title` first and falls back to `name`; no model has
both, so a resource never has to say which one it uses.

While the form is open, `SlugInput::preview()` mirrors the title into the
slug field on create. It calls `Slug::base()` so the preview matches what
gets stored — do not reach for `Str::slug()` here, it skips the length cap
and the empty-value fallback.

## Translated fields are required only in ru and uz

Write a plain `->required()`. `AppServiceProvider::configureTranslatableTabs()`
passes `modifyFieldsUsing()` to the plugin, which hands it the tab's locale,
and narrows the requirement to `config('app.required_locales')`. A field that
did not ask to be required stays optional in every locale.

Do not parse the locale out of `getStatePath()` — the plugin supplies it.
The panel-wide hook also keeps the asterisk honest: it shows on the ru and
uz tabs and not on en, which a per-field closure got wrong for a while.

## Build a field from `app/Filament/Support`, do not paste one

Three classes hold every repeated fragment — `Fields` for form inputs,
`Tables` for columns, filters and actions, `Translated` for the locale
rules. Add a method to one of them rather than a new file: the folder was
sixteen one-method classes and became unreadable.

Both editors are `RichEditor`; they differ in reach.
`Fields::multiline()` has no file uploads and no block buttons — inline
marks only — for headings and leads where the editor mostly supplies a
manual line break. `Fields::editor()` adds headings, lists, tables and
attachments, and belongs on `content`.

Everything either one produces is HTML, so the frontend prints it with
`v-html="rich(value)"`. `rich()` unwraps a lone `<p>` so the value can sit
inside an `<h2>` or a `<p>` without nesting a block element in one.

| Helper | Replaces |
| --- | --- |
| `Fields::slug()` / `::slugPreview()` | the slug field and its live preview |
| `Fields::sort()` | the `sort` number input |
| `Fields::status()` | the publish switch on a form or repeater item |
| `Fields::multiline()` | inline-only editor for headings and leads |
| `Fields::editor()` | the full editor, with attachments, for `content` |
| `Fields::image()` | the image upload with the crop editor |
| `Fields::icon()` | the social-network icon picker |
| `Fields::category($model)` | a picker over one taxonomy model's rows |
| `Tables::statusColumn()` | the publish `ToggleColumn` |
| `Tables::statusFilter()` | the published/unpublished `SelectFilter` |
| `Tables::categoryFilter($model)` | the matching filter over the same rows |
| `Tables::actions()` / `::bulkActions()` | preview/view/edit/delete and bulk-delete |
| `PreviewAction::make()` | the "посмотреть на сайте" button on a record page |
| `Fields::itemLabel($field)` | a repeater item label from a translated field |
| `SaveAction::make(self::class)` | the save button of a homepage tab |

Both take a taxonomy model class and share its `options()`, so a filter always
lists the same rows the form offers. A raw
`SelectFilter::make('category')->relationship('category', 'slug')` shows raw
slugs and skips the cached options. Both return a plain Filament component, so
override the label when the taxonomy is not a category:
`Fields::category(Region::class, 'region_id')->label(__('app.label.region_single'))`.

## The preview button knows every address on the site

`PreviewAction` holds the one map from a model to its URL on the frontend —
including the `/cdma` prefix, which a tariff takes from its category and the
rest from their own `network`. A model missing from that `match` gets no
button anywhere, because the action is `->visible()` on the path resolving.
Add a record type to the site by adding a line there, not by pasting a URL
into a resource.

It is already in `Tables::actions()`, so every listing carries it; a record
page adds `PreviewAction::make()` to `getHeaderActions()`. In a row it is
`->iconButton()` — four labels do not fit, and "Посмотреть на сайте" beside
the view action's "Просмотр" read as the same thing.

The link carries `PreviewToken::for($record)`: one record, one day. What
honours it is `Publishable::resolveRouteBinding()`, which falls back to the
unpublished row only when the token names it, and `PageController`, which
skips the cache while a token is in play. A listing endpoint never does —
a draft in the feed would be published in every way that matters.

## Only the open tab of a block manager is rendered

`ManageBlocks` sets `Tabs::livewireProperty('activeTab')`, so Filament emits
markup for the active tab alone and rebuilds a neighbour on `wire:click`.
Every tab of the homepage at once was 3.3 MB of HTML; one tab is 0.76 MB.

The tabs are keyed by their `ContentBlockKey`, which becomes the value of
`activeTab` and of the `?tab=` query parameter (`#[Url(as: 'tab')]` on the
property, since `persistTabInQueryString()` only works for the Alpine-driven
variant). The key also joins the DOM ids of the fields inside, so an id reads
`form.hero.hero.slides…` — match a suffix, never the whole id.

State is untouched by any of this: `mount()` still loads every block into
`$data`, so a closed tab keeps its unsaved edits and `SaveAction` still finds
its own tab's state.

A test may no longer assert one `->get()` sees fields from several tabs. Drive
the page with `Livewire::test(...)->set('activeTab', 'coverage')` and assert
per tab.

## An application subject is retired, never deleted

`ApplicationTheme` is the only taxonomy whose rows cannot be removed once
something points at them. Applications are records of what a visitor sent;
the subject is the only thing grouping them, and nobody can re-file 3586 of
them by hand. Content taxonomies stay deletable — a news item without a
category is still a news item.

Three layers, and all three are load-bearing:

- the foreign key is `restrictOnDelete`, so no route — Filament, tinker, a
  future console command — can orphan an application;
- the row action carries `->authorize(fn ($record) => ! $record->isInUse())`
  with `->authorizationTooltip()`, so the button is disabled and says why
  instead of throwing a query exception in the admin's face;
- the bulk delete uses `->authorizeIndividualRecords(...)`, which drops the
  used rows from the batch and deletes the rest.

To take a subject out of the form, switch its status off: the public
categories endpoint filters on `published()`, while the admin filter lists
every row, so old applications stay groupable by a retired subject.

## A translated upload lives on the locale tabs, not in a repeater

`Document` keeps its file in a translated JSON column beside the name, so
`TranslatableTabs` shows one upload per locale — no language select, no way
to file two rows under the same language. `CleansUpAttachedFiles` reads
`getTranslations()` for any field that is also in `$translatable`, so every
locale's upload is removed with the row.

A locale with nothing of its own falls back to the default one and then to
whatever is filled, so a document uploaded once is still offered everywhere.
Reach for a repeater only when a record genuinely needs several files per
locale.
