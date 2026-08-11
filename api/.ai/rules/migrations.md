---
globs: database/migrations/**
---

# Migrations

## One migration per table

A table is described by its own `create_*` migration and nothing else. Adding
a column means editing that file, not writing `add_column_to_*`.

The project is re-initialised from scratch (`project:init`), so incremental
migrations buy nothing and leave the schema spread across files. This holds
for published package migrations too — those were folded into their `create`
file already.

After changing a `create_*` migration, the schema has to be rebuilt:

```
php artisan migrate:fresh --seed
```
