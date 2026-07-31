<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('app.label.basic_information'))->schema([
                TextEntry::make('id')
                    ->label('ID'),

                TextEntry::make('name')
                    ->label(__('app.label.name')),

                TextEntry::make('email')
                    ->label(__('app.label.email'))
                    ->copyable(),

                TextEntry::make('email_verified_at')
                    ->label(__('app.label.email_verified_at'))
                    ->dateTime('d.m.Y H:i:s')
                    ->placeholder('—'),

                TextEntry::make('roles.name')
                    ->label(__('app.label.roles'))
                    ->badge()
                    ->color('primary')
                    ->placeholder('—'),
            ])->columns(2)->columnSpanFull(),

            Section::make(__('app.label.dates'))->schema([
                TextEntry::make('created_at')
                    ->label(__('app.label.created_at'))
                    ->dateTime('d.m.Y H:i:s'),

                TextEntry::make('updated_at')
                    ->label(__('app.label.updated_at'))
                    ->dateTime('d.m.Y H:i:s'),
            ])->columns(2)->columnSpanFull()->collapsible(),
        ]);
    }
}
