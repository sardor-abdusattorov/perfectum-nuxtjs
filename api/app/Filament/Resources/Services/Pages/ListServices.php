<?php

namespace App\Filament\Resources\Services\Pages;

use App\Enums\Network;
use App\Filament\Resources\Services\ServiceResource;
use App\Models\Service;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListServices extends ListRecords
{
    protected static string $resource = ServiceResource::class;

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
                ->badge(Service::query()->count()),
        ];

        foreach ([Network::FiveG, Network::Cdma] as $network) {
            $tabs[$network->value] = Tab::make($network->getLabel())
                ->badge(Service::query()->forNetwork($network)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->forNetwork($network));
        }

        return $tabs;
    }
}
