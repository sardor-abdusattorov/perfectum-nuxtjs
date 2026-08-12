<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Filament\Support\Fields;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->schema([
                        Fields::image('users', 'avatar_url')
                            ->label(__('app.label.avatar'))
                            ->avatar()
                            ->imageEditorAspectRatios(['1:1']),

                        TextInput::make('name')
                            ->label(__('app.label.name'))
                            ->required(),

                        TextInput::make('email')
                            ->label(__('app.label.email'))
                            ->email()
                            ->required(),

                        TextInput::make('password')
                            ->label(__('app.label.password'))
                            ->password()
                            ->confirmed()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create'),

                        TextInput::make('password_confirmation')
                            ->label(__('app.label.password_confirmation'))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->password()
                            ->dehydrated(false),

                        Select::make('roles')
                            ->label(__('app.label.roles'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->multiple()
                            ->relationship('roles', 'name'),
                    ]),
            ]);
    }
}
