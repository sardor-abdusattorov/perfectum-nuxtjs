---
globs: config/**, bootstrap/**, public/index.php, artisan, app/**, resources/**
---

# Comments

## Never touch a comment that came with the framework or a package

`config/` holds published Laravel and package configs together with their own
documentation — the `|----` blocks explaining every option. That text is not
ours and is not subject to any cleanup. The same goes for `bootstrap/`,
`public/index.php`, `artisan`, and anything else a package put there.

One comment sweep already broke this: it removed 1653 lines of documentation
from 17 configs — auth, debugbar, permission, filament-shield and the rest.
They were restored verbatim in `5c69083`. A second time would cost more: by
then some settings will have drifted from the package defaults, and there
would be nothing left to tell what was lost.

Our own changes inside such a file are explained in that file's own style —
the same `|----` block, never a `//`. See `display_timezone` in
`config/app.php` and the whole of `config/trustedproxy.php`.

## In our code a comment says why, not what

There is not a single `//` line in `app/`, and that is not an accident. An
explanation goes in a docblock above the method and answers the question the
code cannot: why this and not the obvious thing, and what breaks otherwise.
PHPDoc type annotations (`@param`, `@return`, `@var`, `@mixin`) are not
comments — they always stay.

A comment restating the next line is noise. A comment naming a trap is
insurance. Before deleting a long docblock, check whether it is the only
thing holding up non-obvious behaviour; those live in `Models/News.php`,
`Models/Concerns/Publishable.php`, `Filament/Support/RichContentStateCast.php`,
`Filament/Resources/CoverageLayers/CoverageLayerResource.php`,
`Services/Geo/ShapefileReader.php` and `Providers/AppServiceProvider.php`.

The frontend follows the same rule. Guard the notes on the `ym-disable-keys`
and `ym-hide-content` classes in `pages/help/contact.vue` and
`pages/coverage-area.vue` especially: without them any tidy-up of "unused
classes" strips the masking that keeps a visitor's phone number and home
address out of Webvisor recordings.
