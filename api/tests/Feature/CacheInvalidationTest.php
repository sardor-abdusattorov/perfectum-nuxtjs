<?php

declare(strict_types=1);

use App\Enums\ContentBlockKey;
use App\Enums\MenuLocation;
use App\Enums\Network;
use App\Enums\PageKey;
use App\Models\ContentBlock;
use App\Models\Menu;
use App\Models\NewsCategory;
use App\Models\Page;
use App\Models\PageSettings;
use App\Models\Settings;
use App\Models\SiteSettings;
use App\Models\SiteTranslation;
use App\Models\Social;
use App\Models\TariffCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function siteValue(string $path, string $locale = 'ru'): mixed
{
    return test()->withHeader('Accept-Language', $locale)
        ->getJson(route('api.v1.site'))
        ->assertOk()
        ->json($path);
}

it('shows a renamed site setting straight away', function (): void {
    $phone = SiteSettings::create(['name' => 'phone', 'value' => '+998 71 000 0000']);

    expect(siteValue('data.settings.site.phone'))->toBe('+998 71 000 0000');

    $phone->update(['value' => '+998 98 127 0077']);

    expect(siteValue('data.settings.site.phone'))->toBe('+998 98 127 0077');

    $phone->delete();

    expect(siteValue('data.settings.site.phone'))->toBeNull();
});

it('shows an edited social link straight away', function (): void {
    $social = Social::create(['name' => 'Telegram', 'icon' => 'si-telegram', 'url' => 'https://t.me/old']);

    expect(siteValue('data.socials.0.url'))->toBe('https://t.me/old');

    $social->update(['url' => 'https://t.me/perfectum']);

    expect(siteValue('data.socials.0.url'))->toBe('https://t.me/perfectum');

    $social->delete();

    expect(siteValue('data.socials'))->toBe([]);
});

it('shows a renamed menu item straight away and in both locales', function (): void {
    $item = Menu::create([
        'location' => MenuLocation::Header,
        'name' => ['ru' => 'Тарифы', 'uz' => 'Tariflar'],
        'url' => ['ru' => '/tariffs', 'uz' => '/tariffs'],
    ]);

    expect(siteValue('data.menus.header.0.name'))->toBe('Тарифы')
        ->and(siteValue('data.menus.header.0.name', 'uz'))->toBe('Tariflar');

    $item->update(['name' => ['ru' => 'Тарифы и услуги', 'uz' => 'Tarif va xizmatlar']]);

    expect(siteValue('data.menus.header.0.name'))->toBe('Тарифы и услуги')
        ->and(siteValue('data.menus.header.0.name', 'uz'))->toBe('Tarif va xizmatlar');
});

it('shows an edited interface string straight away', function (): void {
    $line = SiteTranslation::create([
        'category' => 'app',
        'key' => 'header.account',
        'value' => ['ru' => 'Кабинет', 'uz' => 'Kabinet'],
        'is_published' => true,
    ]);

    $account = fn (string $locale = 'ru') => siteValue('data.translations', $locale)['header.account'] ?? null;

    expect($account())->toBe('Кабинет');

    $line->update(['value' => ['ru' => 'Личный кабинет', 'uz' => 'Shaxsiy kabinet']]);

    expect($account())->toBe('Личный кабинет')
        ->and($account('uz'))->toBe('Shaxsiy kabinet');
});

it('shows an edited seo default straight away', function (): void {
    Settings::set('seo.title', ['ru' => 'Perfectum', 'uz' => 'Perfectum']);

    expect(siteValue('data.settings.seo.title'))->toBe('Perfectum');

    Settings::set('seo.title', ['ru' => 'Perfectum 5G', 'uz' => 'Perfectum 5G']);

    expect(siteValue('data.settings.seo.title'))->toBe('Perfectum 5G');
});

it('shows an edited category straight away in every network the site asks for', function (): void {
    $category = TariffCategory::create([
        'name' => ['ru' => 'Мобильная связь', 'uz' => 'Mobil aloqa'],
        'slug' => 'mobilnaya-svyaz',
        'network' => Network::Both,
    ]);

    $ask = fn (?string $network) => test()->getJson(route('api.v1.categories', array_filter([
        'taxonomy' => 'tariff-categories',
        'network' => $network,
    ])))->assertOk()->json('data.0.name');

    expect($ask(null))->toBe('Мобильная связь')
        ->and($ask('5g'))->toBe('Мобильная связь')
        ->and($ask('cdma'))->toBe('Мобильная связь');

    $category->update(['name' => ['ru' => 'Мобильные тарифы', 'uz' => 'Mobil tariflar']]);

    expect($ask(null))->toBe('Мобильные тарифы')
        ->and($ask('5g'))->toBe('Мобильные тарифы')
        ->and($ask('cdma'))->toBe('Мобильные тарифы');
});

it('drops a category from the listing the moment it is unpublished', function (): void {
    $category = NewsCategory::create([
        'name' => ['ru' => 'Компания'], 'slug' => 'kompaniya', 'network' => Network::Both,
    ]);

    $count = fn (): int => count(test()->getJson(
        route('api.v1.categories', ['taxonomy' => 'news-categories'])
    )->assertOk()->json('data'));

    expect($count())->toBe(1);

    $category->update(['status' => false]);

    expect($count())->toBe(0);
});

it('shows an edited page straight away', function (): void {
    $page = Page::create([
        'title' => ['ru' => 'О компании', 'uz' => 'Kompaniya haqida'],
        'slug' => 'o-kompanii',
        'content' => ['ru' => '<p>Было</p>', 'uz' => '<p>Edi</p>'],
    ]);

    $title = fn (string $locale = 'ru') => test()->withHeader('Accept-Language', $locale)
        ->getJson(route('api.v1.pages.show', ['page' => 'o-kompanii']))
        ->assertOk()->json('data.title');

    expect($title())->toBe('О компании')->and($title('uz'))->toBe('Kompaniya haqida');

    $page->update(['title' => ['ru' => 'О нас', 'uz' => 'Biz haqimizda']]);

    expect($title())->toBe('О нас')->and($title('uz'))->toBe('Biz haqimizda');
});

it('shows an edited redirect straight away, however it was pasted', function (): void {
    $page = Page::create([
        'title' => ['ru' => 'Тарифы'],
        'slug' => 'tarify',
        'content' => ['ru' => '<p>x</p>'],
        'redirect_from' => ['https://perfectum.uz/ru/static-pages/old-tariffs/?utm_source=sms'],
        'status' => true,
    ]);

    $redirects = fn (): array => test()->getJson(route('api.v1.redirects'))->assertOk()->json('data');

    expect($redirects())->toBe(['static-pages/old-tariffs' => '/pages/tarify']);

    $page->update(['redirect_from' => ['tariff-plans']]);

    expect($redirects())->toBe(['tariff-plans' => '/pages/tarify']);

    $page->update(['status' => false]);

    expect($redirects())->toBe([]);
});

it('shows edited page meta straight away', function (): void {
    Page::create(['title' => ['ru' => 'Тарифы'], 'slug' => 'tarify', 'content' => ['ru' => '<p>x</p>']]);

    $settings = PageSettings::create([
        'key' => PageKey::Tariffs,
        'meta_title' => ['ru' => 'Тарифы Perfectum'],
    ]);

    expect(siteValue('data.settings.pages.tariffs.title'))->toBe('Тарифы Perfectum');

    $settings->update(['meta_title' => ['ru' => 'Тарифы и пакеты']]);

    expect(siteValue('data.settings.pages.tariffs.title'))->toBe('Тарифы и пакеты');
});

it('shows an edited content block straight away', function (): void {
    ContentBlock::write(PageKey::Documents, ContentBlockKey::PageHero, ['title' => ['ru' => 'Документы']]);

    $title = fn () => test()->getJson(route('api.v1.blocks.show', ['page' => 'documents']))
        ->assertOk()->json('data.blocks.page_hero.title');

    expect($title())->toBe('Документы');

    ContentBlock::write(PageKey::Documents, ContentBlockKey::PageHero, ['title' => ['ru' => 'Документы и отчёты']]);

    expect($title())->toBe('Документы и отчёты');
});
