<?php

declare(strict_types=1);

use App\Filament\Resources\Services\Pages\CreateService;
use App\Filament\Resources\Tariffs\Pages\CreateTariff;
use App\Models\Device;
use App\Models\DeviceBrand;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Tariff;
use App\Models\TariffCategory;
use App\Models\TariffType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();
});

function allowed(string $subject): User
{
    $user = panelUser();

    foreach (['ViewAny', 'View', 'Create', 'Update'] as $ability) {
        $user->givePermissionTo(Permission::findOrCreate("{$ability}:{$subject}", 'web'));
    }

    return tap($user->refresh(), fn (User $user) => test()->actingAs($user));
}

it('creates a tariff with its features and connect buttons', function (): void {
    allowed('Tariff');

    $section = TariffCategory::create([
        'slug' => 'mobilnaya-svyaz',
        'name' => ['ru' => 'Мобильная связь', 'uz' => 'Mobil aloqa'],
        'status' => true,
    ]);

    $chip = TariffType::create([
        'name' => ['ru' => 'Тариф 5G', 'uz' => '5G tarifi'],
        'status' => true,
    ]);

    Livewire::test(CreateTariff::class)
        ->fillForm([
            'category_id' => $section->getKey(),
            'type_id' => $chip->getKey(),
            'name' => ['ru' => "A'lo 5G", 'uz' => "A'lo 5G", 'en' => ''],
            'price' => '100 000',
            'price_currency' => ['ru' => 'сум', 'uz' => 'soʻm', 'en' => 'UZS'],
            'price_period' => ['ru' => '/ 30 дней', 'uz' => '/ 30 kun', 'en' => '/ 30 days'],
            'features' => [
                ['icon' => null, 'title' => ['ru' => '1000 минут', 'uz' => '1000 daqiqa', 'en' => ''], 'note' => ['ru' => 'Исходящие по Узбекистану', 'uz' => '', 'en' => '']],
                ['icon' => null, 'title' => ['ru' => '1000 SMS', 'uz' => '1000 SMS', 'en' => ''], 'note' => ['ru' => '', 'uz' => '', 'en' => '']],
            ],
            'buttons' => [
                ['icon' => null, 'name' => ['ru' => 'Наберите 7*1*1', 'uz' => 'Chaqiruv 7*1*1', 'en' => ''], 'type' => 'tel', 'url' => 'tel:7*1*1'],
                ['icon' => null, 'name' => ['ru' => 'Персональный кабинет', 'uz' => 'Shaxsiy kabinet', 'en' => ''], 'type' => 'link', 'url' => 'https://lk.perfectum.uz'],
            ],
            'sort' => 0,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $tariff = Tariff::query()->sole();

    expect($tariff->slug)->toBe('alo-5g')
        ->and($tariff->price)->toBe('100 000')
        ->and($tariff->features)->toHaveCount(2)
        ->and($tariff->features[0]['title']['ru'])->toBe('1000 минут')
        ->and($tariff->buttons)->toHaveCount(2)
        ->and($tariff->buttons[0]['type'])->toBe('tel')
        ->and($tariff->buttons[1]['url'])->toBe('https://lk.perfectum.uz')
        ->and($tariff->category->is($section))->toBeTrue()
        ->and($tariff->type->is($chip))->toBeTrue();
});

it('translates a tariff into the requested locale', function (): void {
    $tariff = Tariff::create([
        'name' => ['ru' => 'Тариф', 'uz' => 'Tarif'],
        'slug' => 'tarif',
        'price_period' => ['ru' => 'в месяц', 'uz' => 'oyiga'],
        'status' => true,
    ]);

    app()->setLocale('uz');

    expect($tariff->refresh()->name)->toBe('Tarif')
        ->and($tariff->price_period)->toBe('oyiga');
});

it('creates a service with its facts and steps', function (): void {
    allowed('Service');

    $category = ServiceCategory::create([
        'slug' => 'setevye-uslugi',
        'name' => ['ru' => 'Сетевые услуги', 'uz' => 'Tarmoq xizmatlari'],
        'status' => true,
    ]);

    Livewire::test(CreateService::class)
        ->fillForm([
            'category_id' => $category->getKey(),
            'name' => ['ru' => 'Автоплатёж', 'uz' => 'Avtotoʻlov', 'en' => ''],
            'ussd' => '*100*5#',
            'facts' => [
                ['label' => ['ru' => 'Стоимость', 'uz' => 'Narxi', 'en' => ''], 'value' => ['ru' => 'Бесплатно', 'uz' => 'Bepul', 'en' => '']],
            ],
            'steps' => [
                ['text' => ['ru' => 'Наберите USSD', 'uz' => 'USSD tering', 'en' => ''], 'code' => '*100*5#'],
            ],
            'sort' => 0,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $service = Service::query()->sole();

    expect($service->slug)->toBe('avtoplatez')
        ->and($service->ussd)->toBe('*100*5#')
        ->and($service->facts)->toHaveCount(1)
        ->and($service->facts[0]['value']['ru'])->toBe('Бесплатно')
        ->and($service->steps[0]['code'])->toBe('*100*5#')
        ->and($service->category->is($category))->toBeTrue();
});

it('lists both resources in the panel', function (): void {
    allowed('Tariff');
    $this->get('/admin/tariffs')->assertOk();

    allowed('Service');
    $this->get('/admin/services')->assertOk();
});

it('shows the brand by name in the device list', function (): void {
    $user = panelUser();

    foreach (['ViewAny', 'View'] as $verb) {
        $user->givePermissionTo(Permission::findOrCreate("{$verb}:Device", 'web'));
    }

    $brand = DeviceBrand::create(['name' => 'Tozed', 'slug' => 'tozed', 'sort' => 1, 'status' => true]);

    Device::create([
        'name' => ['ru' => 'Tozed ZLT X25'],
        'slug' => 'tozed-zlt-x25',
        'brand_id' => $brand->getKey(),
        'status' => true,
    ]);

    $this->actingAs($user->refresh())
        ->get('/admin/devices')
        ->assertOk()
        ->assertSee('Tozed')
        ->assertDontSee('&quot;slug&quot;:&quot;tozed&quot;', false);
});
