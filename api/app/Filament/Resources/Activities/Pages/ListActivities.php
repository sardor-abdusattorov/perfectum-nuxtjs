<?php

declare(strict_types=1);

namespace App\Filament\Resources\Activities\Pages;

use App\Filament\Resources\Activities\Widgets\ActivityOverviewWidget;
use App\Filament\Resources\Activities\Widgets\ActivityTrendChartWidget;
use App\Filament\Resources\Activities\Widgets\HighRiskActionsChartWidget;
use App\Filament\Resources\Activities\Widgets\TopEventsChartWidget;
use App\Filament\Resources\Activities\Widgets\TopUsersChartWidget;
use MrAdder\FilamentLogger\Resources\ActivityResource\Pages\ListActivities as BasePage;
use MrAdder\FilamentLogger\Support\ActivityFilterPresetManager;

class ListActivities extends BasePage
{
    /**
     * Tab labels live in `filament-logger.activity_filters.saved` as plain
     * strings, so they cannot be translated in config — the config value is
     * kept as the fallback and the label is resolved per request instead.
     */
    public function getTabs(): array
    {
        $tabs = [];

        foreach (ActivityFilterPresetManager::saved() as $key => $preset) {
            $fallback = (string) data_get($preset, 'label', $this->generateTabLabel((string) $key));
            $translationKey = 'filament-logger::filament-logger.tab.'.$key;
            $label = __($translationKey);

            $tabs[$key] = $this->makeTab(is_string($label) && $label !== $translationKey ? $label : $fallback)
                ->icon(data_get($preset, 'icon'))
                ->modifyQueryUsing(fn ($query) => ActivityFilterPresetManager::apply($query, $preset));
        }

        return $tabs;
    }

    protected function getHeaderWidgets(): array
    {
        if (! config('filament-logger.dashboard.enabled', true)) {
            return [];
        }

        $days = (int) config('filament-logger.dashboard.lookback_days', 30);
        $limit = (int) config('filament-logger.dashboard.top_limit', 5);

        return [
            ActivityOverviewWidget::make(['days' => $days]),
            ActivityTrendChartWidget::make(['days' => $days]),
            TopUsersChartWidget::make(['days' => $days, 'limit' => $limit]),
            TopEventsChartWidget::make(['days' => $days, 'limit' => $limit]),
            HighRiskActionsChartWidget::make(['days' => $days, 'limit' => $limit]),
        ];
    }
}
