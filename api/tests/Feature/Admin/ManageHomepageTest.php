<?php

declare(strict_types=1);

use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Filament\Pages\Homepage\HeroTab;
use App\Filament\Pages\ManageHomepage;
use App\Models\ContentBlock;
use App\Models\User;
use Database\Seeders\HomepageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();
});

function homepageAdmin(): User
{
    $user = panelUser(['View:ManageHomepage']);

    $user->givePermissionTo(Permission::findOrCreate('View:ManageHomepage', 'web'));

    return $user->refresh();
}

it('renders one tab at a time and builds the rest on demand', function (): void {
    $this->actingAs(homepageAdmin());

    $page = Livewire::test(ManageHomepage::class)
        ->assertSee('hero.slides')
        ->assertDontSee('choose.cards')
        ->assertDontSee('app_promo.watermark');

    $page->set('activeTab', 'choose')
        ->assertSee('choose.cards')
        ->assertDontSee('hero.slides');

    $page->set('activeTab', 'app_promo')
        ->assertSee('app_promo.watermark');
});

it('offers buttons and both switches inside the hero slide', function (): void {
    ContentBlock::write(PageKey::Home, ContentBlockKey::Hero, [
        'slides' => [
            [
                'title' => ['ru' => 'Скорость'],
                'buttons' => [['label' => ['ru' => 'Подключиться'], 'url' => '/tariffs', 'style' => 'primary']],
                'show_aside' => true,
                'show_gauge' => true,
                'status' => true,
            ],
        ],
    ]);

    $this->actingAs(homepageAdmin())
        ->get('/admin/homepage')
        ->assertOk()
        ->assertSee('show_aside')
        ->assertSee('show_gauge')
        ->assertSee('slide_translations')
        ->assertSee('button_translations');
});

it('keeps buttons and the two hero switches inside a slide', function (): void {
    ContentBlock::write(PageKey::Home, ContentBlockKey::Hero, [
        'slides' => [
            [
                'title' => ['ru' => 'Скорость'],
                'lead' => ['ru' => 'Запуск 5G'],
                'buttons' => [
                    ['label' => ['ru' => 'Подключиться'], 'url' => '/tariffs', 'style' => 'primary', 'status' => true],
                ],
                'show_aside' => false,
                'show_gauge' => true,
                'status' => true,
            ],
        ],
    ]);

    $slide = ContentBlock::read(PageKey::Home, ContentBlockKey::Hero)['slides'][0];

    expect($slide['buttons'])->toHaveCount(1)
        ->and($slide['buttons'][0]['url'])->toBe('/tariffs')
        ->and($slide['show_aside'])->toBeFalse()
        ->and($slide['show_gauge'])->toBeTrue();

    expect(ContentBlock::query()->where('key', ContentBlockKey::Hero)->exists())->toBeTrue();
});

/**
 * The needle reads the number, so a slide that keeps a value the dial cannot
 * point at would show a speed the artwork never marks.
 */
it('keeps the gauge value inside the scale the artwork draws', function (): void {
    ContentBlock::write(PageKey::Home, ContentBlockKey::Hero, [
        'slides' => [
            [
                'title' => ['ru' => 'Скорость'],
                'show_gauge' => true,
                'gauge_value' => 300,
                'status' => true,
            ],
        ],
    ]);

    $slide = ContentBlock::read(PageKey::Home, ContentBlockKey::Hero)['slides'][0];

    expect($slide['gauge_value'])
        ->toBe(300)
        ->toBeLessThanOrEqual(HeroTab::GAUGE_MAX);
});

it('shows the gauge value next to its switch', function (): void {
    ContentBlock::write(PageKey::Home, ContentBlockKey::Hero, [
        'slides' => [
            ['title' => ['ru' => 'Скорость'], 'show_gauge' => true, 'gauge_value' => 1000, 'status' => true],
        ],
    ]);

    $this->actingAs(homepageAdmin())
        ->get('/admin/homepage')
        ->assertOk()
        ->assertSee('gauge_value')
        ->assertSee(__('app.suffix.mbps'));
});

it('keeps the coverage status text and the publish switch apart', function (): void {
    ContentBlock::write(PageKey::Home, ContentBlockKey::Coverage, [
        'cities' => [
            [
                'name' => ['ru' => 'Ташкент'],
                'status_text' => ['ru' => 'Полное покрытие'],
                'active' => true,
                'status' => false,
            ],
        ],
    ]);

    $city = ContentBlock::read(PageKey::Home, ContentBlockKey::Coverage)['cities'][0];

    expect($city['status_text']['ru'])->toBe('Полное покрытие')
        ->and($city['status'])->toBeFalse();

    $this->actingAs(homepageAdmin());

    Livewire::test(ManageHomepage::class)
        ->set('activeTab', 'coverage')
        ->assertSee('status_text');
});

it('loads the seeded copy back into the forms', function (): void {
    $this->seed(HomepageSeeder::class);

    $this->actingAs(homepageAdmin());

    $page = Livewire::test(ManageHomepage::class)
        ->assertSee('Скорость')
        ->assertSee('Подключиться');

    $page->set('activeTab', 'coverage')->assertSee('Ташкент');
    $page->set('activeTab', 'marquee')->assertSee('STANDALONE');
});

it('seeds every block of the home page', function (): void {
    $this->seed(HomepageSeeder::class);

    foreach (ManageHomepage::tabs() as $tab) {
        expect(ContentBlock::read(PageKey::Home, $tab::key()))
            ->not->toBeEmpty("блок {$tab::key()->value} пустой");
    }
});

it('requires the title in ru and uz but not in en', function (): void {
    $this->actingAs(homepageAdmin());

    $html = Livewire::test(ManageHomepage::class)
        ->set('activeTab', 'app_promo')
        ->html();

    $marked = function (string $locale) use ($html): bool {
        $label = strpos($html, "app_promo.title.{$locale}-label");

        return $label !== false
            && str_contains(substr($html, $label, 400), 'fi-fo-field-label-required-mark');
    };

    expect($marked('ru'))->toBeTrue()
        ->and($marked('uz'))->toBeTrue()
        ->and($marked('en'))->toBeFalse();
});
