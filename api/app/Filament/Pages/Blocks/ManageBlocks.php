<?php

namespace App\Filament\Pages\Blocks;

use Filament\Pages\Page;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

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
                        ->schema(array_map(fn (string $tab) => $tab::make(), static::tabs())),
                ]),
            ])
            ->statePath('data');
    }
}
