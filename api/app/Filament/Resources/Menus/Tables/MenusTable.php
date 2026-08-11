<?php

namespace App\Filament\Resources\Menus\Tables;

use App\Enums\MenuLocation;
use App\Enums\PublishedStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MenusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->columns([
                TextColumn::make('name')
                    ->label(__('app.label.name'))
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('parent.name')
                    ->label(__('app.label.parent_item'))
                    ->placeholder('—')
                    ->sortable(),

                TextColumn::make('location')
                    ->label(__('app.label.menu_location'))
                    ->badge()
                    ->formatStateUsing(fn (MenuLocation $state): string => $state->getLabel())
                    ->color(fn (MenuLocation $state): string => match ($state) {
                        MenuLocation::Header, MenuLocation::CdmaHeader => 'primary',
                        MenuLocation::Footer, MenuLocation::CdmaFooter => 'gray',
                    }),

                TextColumn::make('url')
                    ->label(__('app.label.url'))
                    ->placeholder('—')
                    ->wrap(),

                IconColumn::make('open_in_new_tab')
                    ->label(__('app.label.open_in_new_tab'))
                    ->boolean(),

                ToggleColumn::make('status')
                    ->label(__('app.label.show_on_site'))
                    ->sortable()
                    ->onIcon('heroicon-m-check-circle')
                    ->offIcon('heroicon-m-x-circle')
                    ->onColor('success')
                    ->offColor('danger'),

                TextColumn::make('sort')
                    ->label(__('app.label.sort'))
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('location')
                    ->label(__('app.label.menu_location'))
                    ->options(MenuLocation::getLocationOptions()),

                SelectFilter::make('status')
                    ->label(__('app.label.status'))
                    ->options(PublishedStatus::getStatusOptions()),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
