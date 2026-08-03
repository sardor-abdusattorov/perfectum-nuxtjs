<?php

declare(strict_types=1);

namespace App\Filament\Resources\Activities\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat;
use MrAdder\FilamentLogger\Support\ActivityAnalytics;
use MrAdder\FilamentLogger\Widgets\ActivityOverviewWidget as BaseWidget;

class ActivityOverviewWidget extends BaseWidget
{
    public function getHeading(): ?string
    {
        return __('filament-logger::filament-logger.widget.overview.heading');
    }

    /**
     * @return array<int, Stat>
     */
    protected function getStats(): array
    {
        $overview = app(ActivityAnalytics::class)->overview($this->days);

        return [
            $this->withDrillDown(
                Stat::make(__('filament-logger::filament-logger.widget.overview.total'), (string) $overview['total'])
                    ->description(__('filament-logger::filament-logger.widget.overview.total_description', ['days' => $this->days]))
                    ->color('primary'),
                'all_activity',
            ),
            $this->withDrillDown(
                Stat::make(__('filament-logger::filament-logger.widget.overview.high_risk'), (string) $overview['high_risk'])
                    ->description(__('filament-logger::filament-logger.widget.overview.high_risk_description'))
                    ->color('danger'),
                'high_risk_incidents',
            ),
            $this->withDrillDown(
                Stat::make(__('filament-logger::filament-logger.widget.overview.failed_logins'), (string) $overview['failed_logins'])
                    ->description(__('filament-logger::filament-logger.widget.overview.failed_logins_description'))
                    ->color('warning'),
                'failed_logins',
            ),
            $this->withDrillDown(
                Stat::make(__('filament-logger::filament-logger.widget.overview.unique_actors'), (string) $overview['unique_actors'])
                    ->description(__('filament-logger::filament-logger.widget.overview.unique_actors_description'))
                    ->color('success'),
                'all_activity',
            ),
        ];
    }
}
