<?php

declare(strict_types=1);

use App\Filament\Pages\ManageAboutCompany;
use App\Filament\Pages\ManageCdmaConnect;
use App\Filament\Pages\ManageContacts;
use App\Filament\Pages\ManageDocuments;
use App\Filament\Pages\ManageHomepage;
use App\Filament\Pages\Settings;
use App\Filament\Resources\ActionCategories\ActionCategoryResource;
use App\Filament\Resources\Actions\ActionResource;
use App\Filament\Resources\Applications\ApplicationResource;
use App\Filament\Resources\ApplicationThemes\ApplicationThemeResource;
use App\Filament\Resources\CoverageLayers\CoverageLayerResource;
use App\Filament\Resources\DeviceCategories\DeviceCategoryResource;
use App\Filament\Resources\Devices\DeviceResource;
use App\Filament\Resources\DocumentCategories\DocumentCategoryResource;
use App\Filament\Resources\Documents\DocumentResource;
use App\Filament\Resources\FaqCategories\FaqCategoryResource;
use App\Filament\Resources\Faqs\FaqResource;
use App\Filament\Resources\FreeNumberFilters\FreeNumberFilterResource;
use App\Filament\Resources\Menus\MenuResource;
use App\Filament\Resources\News\NewsResource;
use App\Filament\Resources\NewsCategories\NewsCategoryResource;
use App\Filament\Resources\Offices\OfficeResource;
use App\Filament\Resources\Pages\PageResource;
use App\Filament\Resources\PageSettings\PageSettingsResource;
use App\Filament\Resources\Regions\RegionResource;
use App\Filament\Resources\ServiceCategories\ServiceCategoryResource;
use App\Filament\Resources\Services\ServiceResource;
use App\Filament\Resources\SiteSettings\SiteSettingsResource;
use App\Filament\Resources\SiteTranslations\SiteTranslationResource;
use App\Filament\Resources\Socials\SocialResource;
use App\Filament\Resources\TariffCategories\TariffCategoryResource;
use App\Filament\Resources\TariffFiles\TariffFileResource;
use App\Filament\Resources\Tariffs\TariffResource;
use App\Filament\Resources\TariffTypes\TariffTypeResource;
use App\Filament\Resources\Tenders\TenderResource;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Resources\Vacancies\VacancyResource;

/**
 * Two items sharing a sort leave their order to chance, which is how the
 * sidebar drifted before: the entry an editor opens every day sat under the
 * taxonomies that feed it.
 */
it('puts the working item at the top of its group and numbers the rest after it', function (string $group, array $classes): void {
    $sorts = [];

    foreach ($classes as $class) {
        expect($class::getNavigationGroup())->toBe(__("app.group.{$group}"), $class);

        $sorts[] = $class::getNavigationSort();
    }

    expect($sorts)->toBe(range(1, count($classes)));
})->with([
    'applications' => ['applications', [
        ApplicationResource::class,
        ApplicationThemeResource::class,
    ]],
    'content' => ['content', [
        ManageHomepage::class,
        ManageAboutCompany::class,
        ManageContacts::class,
        ManageCdmaConnect::class,
        ManageDocuments::class,
        PageResource::class,
        PageSettingsResource::class,
    ]],
    'tariffs' => ['tariffs', [
        TariffResource::class,
        TariffCategoryResource::class,
        TariffTypeResource::class,
        TariffFileResource::class,
    ]],
    'services' => ['services', [
        ServiceResource::class,
        ServiceCategoryResource::class,
    ]],
    'devices' => ['devices', [
        DeviceResource::class,
        DeviceCategoryResource::class,
    ]],
    'offices' => ['offices', [
        OfficeResource::class,
        RegionResource::class,
        CoverageLayerResource::class,
    ]],
    'resources' => ['resources', [
        NewsResource::class,
        NewsCategoryResource::class,
        ActionResource::class,
        ActionCategoryResource::class,
        FaqResource::class,
        FaqCategoryResource::class,
        DocumentResource::class,
        DocumentCategoryResource::class,
        VacancyResource::class,
        TenderResource::class,
        FreeNumberFilterResource::class,
        MenuResource::class,
        SocialResource::class,
    ]],
    'administration' => ['administration', [
        Settings::class,
        SiteTranslationResource::class,
        SiteSettingsResource::class,
        UserResource::class,
    ]],
]);
