<?php

declare(strict_types=1);

use App\Enums\MenuLocation;
use App\Enums\Network;
use App\Enums\PageKey;
use App\Models\Action;
use App\Models\ActionCategory;
use App\Models\Application;
use App\Models\ApplicationStatus;
use App\Models\ApplicationTheme;
use App\Models\CoverageLayer;
use App\Models\Device;
use App\Models\DeviceBrand;
use App\Models\DeviceCategory;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\FreeNumberFilter;
use App\Models\InstallmentPartner;
use App\Models\Menu;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Office;
use App\Models\Page;
use App\Models\PageSettings;
use App\Models\Region;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\SiteSettings;
use App\Models\SiteTranslation;
use App\Models\Social;
use App\Models\Tariff;
use App\Models\TariffCategory;
use App\Models\TariffFile;
use App\Models\TariffType;
use App\Models\Tender;
use App\Models\User;
use App\Models\Vacancy;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();

    Storage::fake('public');
});

/**
 * @return array<class-string<Model>, callable(): Model>
 */
function crudRecords(): array
{
    return [
        Action::class => fn (): Model => Action::create([
            'title' => ['ru' => 'Акция', 'uz' => 'Aksiya'],
            'slug' => 'akciya',
            'content' => ['ru' => '<p>Текст</p>', 'uz' => '<p>Matn</p>'],
            'network' => Network::FiveG,
            'category_id' => ActionCategory::create([
                'name' => ['ru' => 'Мобильная связь'], 'slug' => 'mobilnaya-svyaz', 'network' => Network::Both,
            ])->getKey(),
        ]),
        ActionCategory::class => fn (): Model => ActionCategory::create([
            'name' => ['ru' => 'Связь', 'uz' => 'Aloqa'], 'slug' => 'svyaz', 'network' => Network::Both,
        ]),
        Application::class => fn (): Model => Application::create([
            'name' => 'Иван', 'phone' => '+998901234567', 'message' => 'Вопрос',
        ]),
        ApplicationTheme::class => fn (): Model => ApplicationTheme::create([
            'name' => ['ru' => 'Подключение', 'uz' => 'Ulanish'],
        ]),
        ApplicationStatus::class => fn (): Model => ApplicationStatus::create([
            'name' => ['ru' => 'Перезвонить', 'uz' => 'Qayta qoʻngʻiroq'], 'slug' => 'perezvonit',
        ]),
        CoverageLayer::class => fn (): Model => CoverageLayer::create([
            'key' => 'coverage-5g', 'name' => ['ru' => 'Покрытие', 'uz' => 'Qamrov'],
        ]),
        Device::class => fn (): Model => Device::create([
            'name' => ['ru' => 'Роутер', 'uz' => 'Router'],
            'slug' => 'router',
            'brand_id' => DeviceBrand::create(['name' => 'Huawei', 'slug' => 'huawei'])->getKey(),
            'category_id' => DeviceCategory::create([
                'name' => ['ru' => 'Роутеры'], 'slug' => 'routery', 'network' => Network::Both,
            ])->getKey(),
        ]),
        DeviceBrand::class => fn (): Model => DeviceBrand::create(['name' => 'Xiaomi', 'slug' => 'xiaomi']),
        DeviceCategory::class => fn (): Model => DeviceCategory::create([
            'name' => ['ru' => 'Модемы', 'uz' => 'Modemlar'], 'slug' => 'modemy', 'network' => Network::Both,
        ]),
        Document::class => fn (): Model => Document::create([
            'name' => ['ru' => 'Устав', 'uz' => 'Nizom'],
            'category_id' => DocumentCategory::create(['name' => ['ru' => 'Отчёты']])->getKey(),
        ]),
        DocumentCategory::class => fn (): Model => DocumentCategory::create([
            'name' => ['ru' => 'Документы', 'uz' => 'Hujjatlar'],
        ]),
        Faq::class => fn (): Model => Faq::create([
            'question' => ['ru' => 'Как проверить баланс?', 'uz' => 'Balansni qanday tekshiraman?'],
            'answer' => ['ru' => '<p>Наберите *100#</p>', 'uz' => '<p>*100# tering</p>'],
            'pages' => [Faq::PAGE_FAQ],
            'category_id' => FaqCategory::create([
                'name' => ['ru' => 'Общие'], 'slug' => 'obshchie', 'network' => Network::Both,
            ])->getKey(),
        ]),
        FaqCategory::class => fn (): Model => FaqCategory::create([
            'name' => ['ru' => 'Тарифы', 'uz' => 'Tariflar'], 'slug' => 'tarify', 'network' => Network::Both,
        ]),
        FreeNumberFilter::class => fn (): Model => FreeNumberFilter::factory()->create(),
        InstallmentPartner::class => fn (): Model => InstallmentPartner::create([
            'name' => 'Alif', 'slug' => 'alif',
        ]),
        Menu::class => fn (): Model => Menu::create([
            'location' => MenuLocation::Header,
            'name' => ['ru' => 'Тарифы', 'uz' => 'Tariflar'],
            'url' => ['ru' => '/tariffs', 'uz' => '/tariffs'],
        ]),
        News::class => fn (): Model => News::create([
            'title' => ['ru' => 'Новость', 'uz' => 'Yangilik'],
            'slug' => 'novost',
            'content' => ['ru' => '<p>Текст</p>', 'uz' => '<p>Matn</p>'],
            'published_at' => now()->subDay(),
            'category_id' => NewsCategory::create([
                'name' => ['ru' => 'Компания'], 'slug' => 'kompaniya', 'network' => Network::Both,
            ])->getKey(),
        ]),
        NewsCategory::class => fn (): Model => NewsCategory::create([
            'name' => ['ru' => 'События', 'uz' => 'Voqealar'], 'slug' => 'sobytiya', 'network' => Network::Both,
        ]),
        Office::class => fn (): Model => Office::create([
            'name' => 'Центральный офис',
            'address' => ['ru' => 'Ташкент, Амира Темура 1', 'uz' => 'Toshkent, Amir Temur 1'],
            'region_id' => Region::create(['name' => ['ru' => 'Ташкент', 'uz' => 'Toshkent']])->getKey(),
        ]),
        Page::class => fn (): Model => Page::create([
            'title' => ['ru' => 'О компании', 'uz' => 'Kompaniya haqida'],
            'slug' => 'o-kompanii',
            'content' => ['ru' => '<p>Текст</p>', 'uz' => '<p>Matn</p>'],
        ]),
        PageSettings::class => fn (): Model => PageSettings::create(['key' => PageKey::Tariffs]),
        Region::class => fn (): Model => Region::create(['name' => ['ru' => 'Самарканд', 'uz' => 'Samarqand']]),
        Service::class => fn (): Model => Service::create([
            'name' => ['ru' => 'Услуга', 'uz' => 'Xizmat'],
            'slug' => 'usluga',
            'network' => Network::FiveG,
            'category_id' => ServiceCategory::create([
                'name' => ['ru' => 'Интернет'], 'slug' => 'internet', 'network' => Network::Both,
            ])->getKey(),
        ]),
        ServiceCategory::class => fn (): Model => ServiceCategory::create([
            'name' => ['ru' => 'Связь', 'uz' => 'Aloqa'], 'slug' => 'svyaz-uslugi', 'network' => Network::Both,
        ]),
        SiteSettings::class => fn (): Model => SiteSettings::create(['name' => 'phone', 'value' => '+998 98 127 0077']),
        SiteTranslation::class => fn (): Model => SiteTranslation::create([
            'category' => 'app', 'key' => 'cookie.accept', 'value' => ['ru' => 'Принять', 'uz' => 'Qabul qilish'],
        ]),
        Social::class => fn (): Model => Social::create([
            'name' => 'Telegram', 'icon' => 'si-telegram', 'url' => 'https://t.me/perfectum',
        ]),
        Tariff::class => fn (): Model => Tariff::create([
            'name' => ['ru' => 'Тариф', 'uz' => 'Tarif'],
            'slug' => 'tarif',
            'category_id' => TariffCategory::create([
                'name' => ['ru' => 'Мобильная связь'], 'slug' => 'mobilnaya-svyaz-t', 'network' => Network::Both,
            ])->getKey(),
        ]),
        TariffCategory::class => fn (): Model => TariffCategory::create([
            'name' => ['ru' => 'Интернет', 'uz' => 'Internet'], 'slug' => 'internet-t', 'network' => Network::Both,
        ]),
        TariffFile::class => function (): Model {
            Storage::disk('public')->put('files/archive.pdf', 'pdf');

            return TariffFile::create(['name' => 'Архив 2025', 'file' => 'files/archive.pdf']);
        },
        TariffType::class => fn (): Model => TariffType::create([
            'name' => ['ru' => 'Безлимит', 'uz' => 'Cheksiz'], 'slug' => 'bezlimit',
        ]),
        Tender::class => fn (): Model => Tender::create([
            'title' => ['ru' => 'Тендер', 'uz' => 'Tender'],
            'slug' => 'tender',
            'content' => ['ru' => '<p>Текст</p>', 'uz' => '<p>Matn</p>'],
        ]),
        Role::class => fn (): Model => Role::findOrCreate('editor', 'web'),
        User::class => fn (): Model => tap(
            User::factory()->create(),
            fn (User $user) => $user->assignRole(Role::findOrCreate('editor', 'web')),
        ),
        Vacancy::class => fn (): Model => Vacancy::create([
            'title' => ['ru' => 'Инженер', 'uz' => 'Muhandis'],
            'slug' => 'inzhener',
            'content' => ['ru' => '<p>Текст</p>', 'uz' => '<p>Matn</p>'],
        ]),
    ];
}

/**
 * @return array<int, class-string<Model>>
 */
function readOnlyModels(): array
{
    return [Activity::class];
}

/**
 * @return class-string
 */
function resourceFor(string $model): string
{
    $resource = collect(Filament::getPanel('admin')->getResources())
        ->first(fn (string $resource): bool => $resource::getModel() === $model);

    expect($resource)->not->toBeNull("no resource registered for {$model}");

    return $resource;
}

function grantEveryVerb(string $model): User
{
    $user = panelUser();
    $subject = class_basename($model);

    foreach (['ViewAny', 'View', 'Create', 'Update', 'Delete', 'DeleteAny', 'Restore', 'RestoreAny', 'ForceDelete', 'ForceDeleteAny', 'Replicate', 'Reorder'] as $verb) {
        $user->givePermissionTo(Permission::findOrCreate("{$verb}:{$subject}", 'web'));
    }

    return tap($user->refresh(), fn (User $user) => test()->actingAs($user));
}

it('has a record for every resource in the panel', function (): void {
    $covered = [...array_keys(crudRecords()), ...readOnlyModels()];

    $missing = collect(Filament::getPanel('admin')->getResources())
        ->map(fn (string $resource): string => $resource::getModel())
        ->reject(fn (string $model): bool => in_array($model, $covered, true))
        ->values()
        ->all();

    expect($missing)->toBe([]);
});

it('opens, saves and deletes a record in every resource', function (string $model): void {
    $resource = resourceFor($model);
    $pages = $resource::getPages();

    grantEveryVerb($model);

    $record = crudRecords()[$model]();

    $this->get($resource::getUrl('index'))->assertOk();

    if (isset($pages['view'])) {
        $this->get($resource::getUrl('view', ['record' => $record]))->assertOk();
    }

    if (isset($pages['create'])) {
        $this->get($resource::getUrl('create'))->assertOk();
    }

    if (isset($pages['edit'])) {
        $this->get($resource::getUrl('edit', ['record' => $record]))->assertOk();

        Livewire::test($pages['edit']->getPage(), ['record' => $record->getRouteKey()])
            ->call('save')
            ->assertHasNoFormErrors();
    }

    Livewire::test($pages['index']->getPage())
        ->callAction(TestAction::make('delete')->table($record));

    expect($model::query()->whereKey($record->getKey())->exists())->toBeFalse();
})->with(array_keys(crudRecords()));
