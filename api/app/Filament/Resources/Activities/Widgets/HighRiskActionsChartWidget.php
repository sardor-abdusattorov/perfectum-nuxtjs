<?php

declare(strict_types=1);

namespace App\Filament\Resources\Activities\Widgets;

use Illuminate\Contracts\Support\Htmlable;
use MrAdder\FilamentLogger\Widgets\HighRiskActionsChartWidget as BaseWidget;

class HighRiskActionsChartWidget extends BaseWidget
{
    public function getHeading(): string|Htmlable|null
    {
        return $this->activityReviewHeadingForPlaybook(
            __('filament-logger::filament-logger.widget.high_risk.heading'),
            'high_risk_incidents',
        );
    }

    protected function getData(): array
    {
        $data = parent::getData();
        $data['datasets'][0]['label'] = __('filament-logger::filament-logger.widget.high_risk.dataset');

        return $data;
    }
}
