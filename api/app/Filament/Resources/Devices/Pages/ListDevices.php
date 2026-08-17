<?php

namespace App\Filament\Resources\Devices\Pages;

use App\Enums\Network;
use App\Filament\Resources\Devices\DeviceResource;
use App\Models\Device;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListDevices extends ListRecords
{
    protected static string $resource = DeviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    /**
     * @return array<string, Tab>
     */
    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make(__('app.label.all'))
                ->badge(Device::query()->count()),
        ];

        foreach ([Network::FiveG, Network::Cdma] as $network) {
            $tabs[$network->value] = Tab::make($network->getLabel())
                ->badge(Device::query()->forNetwork($network)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->forNetwork($network));
        }

        return $tabs;
    }
}
