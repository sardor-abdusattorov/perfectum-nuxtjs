# Perfectum

Сайт оператора: `perfectum.uz` (5G) и раздел CDMA на том же домене. Прежняя версия живёт на `v2.perfectum.uz`.

## Что где лежит

| Папка | Что это |
| --- | --- |
| `api/` | Laravel — админка на `/dashboard` (Filament) и публичное API `/api/v1`. Контент, заявки, настройки, карта покрытия |
| `frontend/` | Nuxt в режиме серверного рендеринга. Сам ничего не хранит, всё берёт из API |
| `.github/workflows/deploy.yml` | Тесты, сборка и выкладка на боевой сервер |

Правила и решённые вопросы проекта — в `api/.ai/rules/` и `api/CLAUDE.md`. Их стоит прочитать раньше кода: там записаны ловушки, на которых уже спотыкались.

## Локальный запуск

Нужны PHP 8.5, Composer, Node 24, pnpm и база (MySQL или SQLite).

```bash
cd api
composer install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm ci && npm run build          # ассеты админки
php artisan serve                # http://localhost:8000

cd ../frontend
cp .env.example .env
pnpm install
pnpm dev                         # http://localhost:3000
```

Фронтенд ходит в API по адресу из `NUXT_API_BASE`; по умолчанию это `http://localhost:8000/api/v1`.

Проверки:

```bash
cd api && php artisan test --compact && vendor/bin/pint
cd frontend && pnpm build
```

## Выкладка

`push` в `main` запускает `.github/workflows/deploy.yml`: тесты, сборка фронта, затем выкладка на self-hosted runner `website-srv-01`.

На сервере выполняется `git reset --hard origin/main`, ставятся зависимости, собираются ассеты, прогоняется `php artisan project:update` (миграции и чистка кешей), кешируются конфиг, маршруты и представления, перезапускается служба `perfectum-v2-nuxt`, после чего проверяется, что фронтенд отвечает.

Чего выкладка **не** делает: не снимает резервную копию базы, не умеет откатываться, не перезапускает PHP-FPM и не проверяет бэкенд — только фронтенд.

Две команды, которые нельзя запускать на боевом сервере: `php artisan project:init` и `php artisan migrate:fresh` — обе стирают базу.

Внутри `project:update` идут `roles:repair` и `shield:super-admin --user=1`. Значит роли правятся на сервере, а не в панели: `roles:repair` возвращает `panel_user` каждому, у кого её нет, поэтому снятая через панель роль вернётся при следующей выкладке. И если пользователя №1 удалить, `shield:super-admin` упадёт, а шаг идёт под `set -euo pipefail` — выкладка перестанет проходить.

## Требования к серверу

Это не настраивается из репозитория и должно стоять в nginx и PHP на боевой машине.

| Значение | Зачем |
| --- | --- |
| `client_max_body_size 60M` (nginx) | загрузка документов и архива карты покрытия |
| `upload_max_filesize 50M`, `post_max_size 60M` | то же |
| `max_input_vars 10000` | большие формы админки. При стандартной 1000 длинная форма молча обрежется при сохранении, и часть данных пропадёт |
| `max_execution_time 300`, `fastcgi_read_timeout 300` | разбор шейп-файлов карты покрытия |
| `memory_limit 1G` | разбор тех же файлов |
| `date.timezone = Asia/Tashkent` | время в админке и в заявках |
| Запрет исполнять PHP внутри `/storage/` | Загрузки из админки лежат под веб-корнем. Без этого правила файл с расширением `.php`, попавший в хранилище, выполнится сервером |

Запрос к `/api` nginx отдаёт напрямую в PHP-FPM, минуя Nuxt.

## Что держать вне репозитория

`APP_KEY` боевого `.env` (без него не расшифровать сессии и зашифрованные поля), пароль базы, токен биллинга, ключ Яндекс-карт, боевой конфиг nginx, юнит systemd `perfectum-v2-nuxt`, токен self-hosted runner и правило `/etc/sudoers.d/perfectum-deploy`.
