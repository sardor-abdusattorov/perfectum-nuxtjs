<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Homepage\AppPromoTab;
use App\Filament\Pages\Homepage\ChooseTab;
use App\Filament\Pages\Homepage\CoverageTab;
use App\Filament\Pages\Homepage\FeaturesTab;
use App\Filament\Pages\Homepage\HeroTab;
use App\Filament\Pages\Homepage\MarqueeTab;
use App\Filament\Pages\Homepage\TariffsTab;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

/**
 * @property-read Schema $form
 */
class ManageHomepage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected string $view = 'filament.pages.manage-homepage';

    protected static ?string $slug = 'homepage';

    /**
     * @var array<string, mixed>
     */
    public array $data = [];

    public static function getNavigationLabel(): string
    {
        return __('app.label.homepage');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('app.label.content');
    }

    public static function getNavigationSort(): int
    {
        return 1;
    }

    public function getTitle(): string
    {
        return __('app.label.homepage');
    }

    public function mount(): void
    {
        $this->data = [
            'hero' => HeroTab::load(),
            'marquee' => MarqueeTab::load(),
            'choose' => ChooseTab::load(),
            'tariffs' => TariffsTab::load(),
            'features' => FeaturesTab::load(),
            'coverage' => CoverageTab::load(),
            'app_promo' => AppPromoTab::load(),
        ];

        $this->form->fill($this->data);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    Tabs::make('sections')
                        ->columnSpanFull()
                        ->persistTabInQueryString()
                        ->schema([
                            HeroTab::make(),
                            MarqueeTab::make(),
                            ChooseTab::make(),
                            TariffsTab::make(),
                            FeaturesTab::make(),
                            CoverageTab::make(),
                            AppPromoTab::make(),
                        ]),
                ]),
            ])
            ->statePath('data');
    }
}
