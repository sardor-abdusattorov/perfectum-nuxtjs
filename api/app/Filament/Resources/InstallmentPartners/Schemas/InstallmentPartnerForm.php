<?php

namespace App\Filament\Resources\InstallmentPartners\Schemas;

use App\Filament\Support\Fields;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InstallmentPartnerForm
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
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(Fields::slugPreview())
                            ->maxLength(255),

                        Fields::slug(),

                        Fields::image('installment-partners', 'logo')
                            ->label(__('app.label.logo'))
                            ->helperText(__('app.helper.installment_partner_logo')),

                        TextInput::make('url')
                            ->label(__('app.label.link'))
                            ->url()
                            ->maxLength(255),

                        Fields::sort(),

                        Fields::status(),
                    ]),
            ]);
    }
}
