<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class GreetingWidget extends Widget
{
    protected string $view = 'filament.widgets.greeting-widget';

    protected int | string | array $columnSpan = 'full';

    public function getGreeting(): string
    {
        $hour = Carbon::now()->hour;

        return match (true) {
            $hour >= 5 && $hour < 12 => __('app.greeting.morning'),
            $hour >= 12 && $hour < 17 => __('app.greeting.afternoon'),
            $hour >= 17 && $hour < 22 => __('app.greeting.evening'),
            default => __('app.greeting.night'),
        };
    }

    public function getUserName(): string
    {
        return auth()->user()->name ?? __('app.greeting.welcome');
    }
}
