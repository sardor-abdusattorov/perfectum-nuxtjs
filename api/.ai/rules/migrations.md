---
globs: database/migrations/**
---

# Migrations

## The site is live — never rebuild the schema

`migrate:fresh` and `project:init` drop every table. Both are reachable on the
production server, and the deploy takes no backup and cannot roll back. The
database holds thousands of applications from real visitors; there is nowhere
to get them from again.

This rule used to say the opposite — edit the `create_*` migration and run
`php artisan migrate:fresh --seed`. That was written while the project was
still being built from scratch. It has been wrong since launch: Laravel never
re-opens a migration it has recorded, so a column added to `create_*` reaches
a fresh clone and never reaches production, and the rebuild that would fix
that erases the site.

## A change to a live table gets its own migration

Add the column in a new file, guarded so it is safe to run twice and safe on a
database that already has it:

```php
public function up(): void
{
    if (Schema::hasColumn('news', 'by_link')) {
        return;
    }

    Schema::table('news', function (Blueprint $table): void {
        $table->boolean('by_link')->default(false)->after('status');
    });
}
```

Give existing rows the behaviour they had before the column existed — a
default, or a backfill in the same migration. `2026_09_04_120000_add_network_to_faqs.php`
does both and is the pattern to copy; `2026_09_15_120000_add_by_link_to_news.php`
is the smaller version.

The deploy runs `php artisan project:update`, which applies migrations with
`--force` and clears the caches. Nothing else is needed.

## The `create_*` files describe the original schema, not the current one

Leave them as they are. Reading them alone will not tell you what a table
looks like today — check the later migrations too, or ask the database.
