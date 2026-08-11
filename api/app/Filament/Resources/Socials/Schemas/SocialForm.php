<?php

namespace App\Filament\Resources\Socials\Schemas;

use App\Filament\Support\IconPicker;
use App\Filament\Support\SortInput;
use App\Filament\Support\StatusToggle;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SocialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('app.label.name'))
                            ->helperText(__('app.helper.social_name'))
                            ->required(),

                        IconPicker::make(),

                        TextInput::make('url')
                            ->label(__('app.label.url'))
                            ->url()
                            ->required(),

                        SortInput::make(),

                        StatusToggle::make(),
                    ]),
            ]);
    }
}
