<?php

namespace App\Filament\Pages\Blocks;

use Filament\Pages\Page;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Livewire\Attributes\Url;

/**
 * @property-read Schema $form
 */
abstract class ManageBlocks extends Page
{
    protected string $view = 'filament.pages.manage-blocks';

    /**
     * @var array<string, mixed>
     */
    public array $data = [];

    #[Url(as: 'tab')]
    public string $activeTab = '';

    /**
     * @return array<int, class-string<ContentTab>>
     */
    abstract public static function tabs(): array;

    public static function getNavigationGroup(): ?string
    {
        return __('app.group.content');
    }

    public function mount(): void
    {
        foreach (static::tabs() as $tab) {
            $this->data[$tab::key()->value] = $tab::load();
        }

        if (! array_key_exists($this->activeTab, $this->data)) {
            $this->activeTab = (string) array_key_first($this->data);
        }

        $this->form->fill($this->data);
    }

    public function form(Schema $schema): Schema
    {
        $tabs = [];

        foreach (static::tabs() as $tab) {
            $tabs[$tab::key()->value] = $tab::make();
        }

        return $schema
            ->components([
                Form::make([
                    Tabs::make('sections')
                        ->columnSpanFull()
                        ->livewireProperty('activeTab')
                        ->schema($tabs),
                ]),
            ])
            ->statePath('data');
    }
}
