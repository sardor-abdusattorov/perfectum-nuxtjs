<?php

declare(strict_types=1);

namespace App\Filament\Resources\Activities\Widgets;

use Illuminate\Contracts\Support\Htmlable;
use MrAdder\FilamentLogger\Support\ActivityAnalytics;
use MrAdder\FilamentLogger\Widgets\ActivityTrendChartWidget as BaseWidget;

class ActivityTrendChartWidget extends BaseWidget
{
    public function getHeading(): string|Htmlable|null
    {
        return $this->activityReviewHeadingForPlaybook(
            __('filament-logger::filament-logger.widget.trend.heading'),
            'all_activity',
        );
    }

    protected function getData(): array
    {
        $data = parent::getData();
        $data['datasets'][0]['label'] = __('filament-logger::filament-logger.widget.trend.dataset');

        return $data;
    }
}
