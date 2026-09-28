<?php

declare(strict_types=1);

use App\Filament\Support\RichContentStateCast;
use App\Models\News;
use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Три списка отвечают на три разных вопроса, и путать их дорого. Язык интерфейса
 * упирается в наличие переводов панели, языки контента — ни во что, языки сайта
 * — в готовность витрины. Контент имеет право опережать сайт: провайдер
 * заполняет английский месяцами, пока раздела /en/ ещё нет.
 */
it('keeps every narrower list inside the content one', function (): void {
    expect(array_diff(panel_locales(), app_locales()))->toBe([])
        ->and(array_diff(site_locales(), app_locales()))->toBe([])
        ->and(array_diff((array) config('app.required_locales'), app_locales()))->toBe([]);
});

it('keeps the default locale in all three', function (): void {
    $default = config('app.locale');

    expect(app_locales())->toContain($default)
        ->and(panel_locales())->toContain($default)
        ->and(site_locales())->toContain($default);
});

it('offers the panel only languages it is actually translated into', function (): void {
    foreach (panel_locales() as $locale) {
        foreach (['app', 'auth', 'pagination', 'passwords', 'validation'] as $file) {
            expect(lang_path("{$locale}/{$file}.php"))->toBeFile("нет перевода панели: {$locale}/{$file}.php");
        }
    }
});

/**
 * TranslatableTabs индексирует карту подписей без проверки на существование, так
 * что пропущенный ключ роняет каждую переводимую форму в админке.
 */
it('names every content locale in both panel languages', function (): void {
    foreach (app_locales() as $locale) {
        foreach (panel_locales() as $panel) {
            expect(__("app.label.{$locale}", locale: $panel))->not->toBe("app.label.{$locale}");
        }
    }
});

it('switches the panel between its own languages, not the content ones', function (): void {
    expect(LanguageSwitch::make()->getLocales())->toBe(panel_locales())
        ->and(panel_locales())->not->toBe(app_locales());
});

/**
 * Приложение может читать язык, которого на сайте ещё нет, — для того контентный
 * список и шире.
 */
it('answers in a content locale the site does not serve yet', function (): void {
    expect(app_locales())->toContain('en')
        ->and(site_locales())->not->toContain('en');

    $this->getJson(route('api.v1.site'), ['X-Locale' => 'en'])
        ->assertOk()
        ->assertJsonPath('data.settings.locale', 'en');
});

/**
 * А вот угадывать по браузеру можно только среди языков сайта. Иначе английский
 * браузер получил бы язык, на котором нет ни страниц, ни карты сайта, — и это
 * не редкость: такой Accept-Language шлёт даже тестовый клиент.
 */
it('never guesses a language the site does not serve', function (): void {
    $this->withServerVariables(['HTTP_ACCEPT_LANGUAGE' => 'en-us,en;q=0.5'])
        ->getJson(route('api.v1.site'))
        ->assertOk()
        ->assertJsonPath('data.settings.locale', 'ru');
});

it('falls back for a record nobody has translated yet', function (): void {
    News::create([
        'title' => ['ru' => 'Технические работы', 'uz' => 'Texnik ishlar'],
        'slug' => 'raboty',
        'content' => ['ru' => '<p>Текст</p>'],
        'published_at' => now()->subDay(),
        'status' => true,
    ]);

    $this->getJson(route('api.v1.news.show', ['news' => 'raboty']), ['X-Locale' => 'en'])
        ->assertOk()
        ->assertJsonPath('data.title', 'Технические работы')
        ->assertJsonPath('data.content', '<p>Текст</p>');
});

/**
 * Нетронутый редактор отдаёт документ с пустым абзацем, а не пустую строку.
 * Сохранить такое значит выключить откат: на новом языке у записи оказался бы
 * русский заголовок над пустым телом.
 */
it('does not mistake an untouched editor for filled content', function (): void {
    $cast = new RichContentStateCast;

    expect($cast->get('<p></p>'))->toBeNull()
        ->and($cast->get(''))->toBeNull()
        ->and($cast->get('<p>Текст</p>'))->toBe('<p>Текст</p>');
});
