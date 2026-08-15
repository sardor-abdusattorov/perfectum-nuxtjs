<?php

namespace App\Filament\Resources\Offices\Pages;

use App\Enums\Network;
use App\Enums\OfficeType;
use App\Filament\Resources\Offices\OfficeResource;
use App\Models\Office;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListOffices extends ListRecords
{
    protected static string $resource = OfficeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make(__('app.label.all'))
                ->badge(Office::query()->count()),
        ];

        foreach (OfficeType::cases() as $type) {
            $tabs[$type->value] = Tab::make($type->getLabel().' 5G')
                ->badge(Office::query()->where('type', $type)->where('network', '!=', Network::Cdma)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', $type)->where('network', '!=', Network::Cdma));
        }

        $tabs['cdma'] = Tab::make(__('app.label.cdma_dealers'))
            ->badge(Office::query()->where('network', Network::Cdma)->count())
            ->modifyQueryUsing(fn (Builder $query) => $query->where('network', Network::Cdma));

        return $tabs;
    }
}
