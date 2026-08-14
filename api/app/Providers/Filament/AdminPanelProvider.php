<?php

namespace App\Providers\Filament;

use App\Filament\Pages\ProfileSettings;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use BezhanSalleh\FilamentShield\Support\Utils;
use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use Caresome\FilamentAuthDesigner\Enums\MediaPosition;
use Filament\Actions\Action;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->authGuard('web')
            ->passwordReset()
            ->userMenuItems([
                'profile' => fn (Action $action) => $action
                    ->label(fn () => __('app.label.my_profile'))
                    ->url(fn (): string => ProfileSettings::getUrl())
                    ->icon('heroicon-o-user-circle'),
            ])
            ->login()
            ->brandLogo(asset('images/logo.svg'))
            ->brandLogoHeight('3.5rem')
            ->defaultThemeMode(ThemeMode::Light)
            ->colors([
                'primary' => Color::Blue,
            ])
            ->spa(false)
            ->navigationItems([
                NavigationItem::make()
                    ->label(fn () => __('app.label.go_to_site'))
                    ->url(fn () => config('app.frontend_url'), shouldOpenInNewTab: true)
                    ->icon('heroicon-o-globe-alt')
                    ->sort(2),
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label(fn () => __('app.group.content')),

                NavigationGroup::make()
                    ->label(fn () => __('app.group.tariffs')),

                NavigationGroup::make()
                    ->label(fn () => __('app.group.services')),

                NavigationGroup::make()
                    ->label(fn () => __('app.group.devices')),

                NavigationGroup::make()
                    ->label(fn () => __('app.group.offices')),

                NavigationGroup::make()
                    ->label(fn () => __('app.group.resources')),

                NavigationGroup::make()
                    ->label(fn () => __('app.group.administration')),
            ])
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth(Width::Full)
            ->unsavedChangesAlerts()
            ->databaseTransactions()
            ->databaseNotifications()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup(fn () => __('app.group.administration'))
                    ->navigationSort(5)
                    ->navigationBadge(fn (): string => (string) Utils::getRoleModel()::count()),

                AuthDesignerPlugin::make()
                    ->defaults(fn ($config) => $config
                        ->media(asset('/images/background.jpg'))
                        ->mediaPosition(MediaPosition::Right)
                        ->mediaSize('60%')
                    )
                    ->login()
                    ->passwordReset()
                    ->emailVerification()
                    ->themeToggle(),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->resources([
                config('filament-logger.activity_resource'),
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css');
    }
}
