<?php

namespace App\Providers;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Resources\Activities\Widgets\ActivityOverviewWidget;
use App\Filament\Resources\Activities\Widgets\ActivityTrendChartWidget;
use App\Filament\Resources\Activities\Widgets\HighRiskActionsChartWidget;
use App\Filament\Resources\Activities\Widgets\TopEventsChartWidget;
use App\Filament\Resources\Activities\Widgets\TopUsersChartWidget;
use App\Models\ContentBlock;
use App\Models\Menu;
use App\Models\Page;
use App\Models\PageSettings;
use App\Models\Settings;
use App\Models\SiteSettings;
use App\Models\SiteTranslation;
use App\Models\Social;
use App\Observers\ContentBlockObserver;
use App\Observers\MenuObserver;
use App\Observers\PageObserver;
use App\Observers\PageSettingsObserver;
use App\Observers\SettingsObserver;
use App\Observers\SiteSettingsObserver;
use App\Observers\SiteTranslationObserver;
use App\Observers\SocialObserver;
use App\Observers\TaxonomyObserver;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use BezhanSalleh\LanguageSwitch\Enums\Placement;
use BezhanSalleh\LanguageSwitch\Enums\PlacementMode;
use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Filament\Forms\Components\Field;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Columns\Column;
use Filament\Tables\Table;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->configureRenderHooks();
    }

    private function configureRenderHooks(): void
    {
        FilamentView::registerRenderHook(
            'panels::head.start',
            fn (): string => '<meta name="robots" content="noindex,nofollow">'
        );

        FilamentView::registerRenderHook(
            'panels::head.end',
            fn (): string => '
                <link rel="apple-touch-icon" sizes="180x180" href="'.asset('/images/favicon/apple-touch-icon.png').'">
                <link rel="icon" type="image/png" sizes="32x32" href="'.asset('/images/favicon/favicon-32x32.png').'">
                <link rel="icon" type="image/png" sizes="16x16" href="'.asset('/images/favicon/favicon-16x16.png').'">
                <link rel="manifest" href="'.asset('/images/favicon/site.webmanifest').'">
            '
        );
    }

    public function boot(): void
    {
        $this->configureObservers();
        $this->configureDB();
        $this->configureModels();
        $this->configureFilament();
        $this->configureActivityLogWidgets();
        $this->configureLimit();
        $this->configureLanguageSwitch();
        $this->configureTranslatableTabs();
    }

    private function configureObservers(): void
    {
        foreach (taxonomies() as $taxonomy) {
            $taxonomy::observe(TaxonomyObserver::class);
        }

        ContentBlock::observe(ContentBlockObserver::class);
        Menu::observe(MenuObserver::class);
        Page::observe(PageObserver::class);
        PageSettings::observe(PageSettingsObserver::class);
        Settings::observe(SettingsObserver::class);
        SiteSettings::observe(SiteSettingsObserver::class);
        SiteTranslation::observe(SiteTranslationObserver::class);
        Social::observe(SocialObserver::class);
    }

    private function configureDB(): void
    {
        DB::prohibitDestructiveCommands($this->app->environment('production'));
    }

    private function configureModels(): void
    {
        Model::preventAccessingMissingAttributes();

        Model::unguard();
    }

    private function configureFilament(): void
    {
        FilamentShield::prohibitDestructiveCommands($this->app->isProduction());

        FilamentShield::enforcePolicies();

        Column::configureUsing(fn (Column $column) => $column->toggleable());

        Table::configureUsing(fn (Table $table) => $table
            ->reorderableColumns()
            ->deferColumnManager(false)
            ->deferFilters(false)
            ->paginationPageOptions([10, 25, 50])
        );
    }

    private function configureActivityLogWidgets(): void
    {
        $widgets = [
            'app.filament.resources.activities.widgets.activity-overview-widget' => ActivityOverviewWidget::class,
            'app.filament.resources.activities.widgets.activity-trend-chart-widget' => ActivityTrendChartWidget::class,
            'app.filament.resources.activities.widgets.top-users-chart-widget' => TopUsersChartWidget::class,
            'app.filament.resources.activities.widgets.top-events-chart-widget' => TopEventsChartWidget::class,
            'app.filament.resources.activities.widgets.high-risk-actions-chart-widget' => HighRiskActionsChartWidget::class,
        ];

        foreach ($widgets as $name => $widget) {
            Livewire::component($name, $widget);
        }
    }

    private function configureLimit(): void
    {
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(600)->by($request->user()?->id ?: $request->ip()));

        RateLimiter::for('upstream', fn (Request $request) => Limit::perMinute(30)->by($request->ip()));
    }

    private function configureLanguageSwitch(): void
    {
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(app_locales())
                ->labels($this->localeLabels())
                ->visible(outsidePanels: true)
                ->outsidePanelPlacement(Placement::TopStart, PlacementMode::Pinned)
                ->outsidePanelRoutes(['auth.']);
        });
    }

    private function configureTranslatableTabs(): void
    {
        TranslatableTabs::configureUsing(function (TranslatableTabs $component) {
            $component
                ->localesLabels($this->localeLabels())
                ->locales(app_locales())
                ->addDirectionByLocale()
                ->addEmptyBadgeWhenAllFieldsAreEmpty(emptyLabel: __('app.label.empty'))
                ->addSetActiveTabThatHasValue()
                ->modifyFieldsUsing(fn (Field $field, string $locale) => $field->required(
                    $field->isRequired() && in_array($locale, (array) config('app.required_locales'), true)
                ));
        });
    }

    /**
     * @return array<string, string>
     */
    private function localeLabels(): array
    {
        return collect(app_locales())
            ->mapWithKeys(fn (string $locale): array => [$locale => __("app.label.{$locale}")])
            ->all();
    }
}
