<?php

declare(strict_types=1);

namespace App\Filament\Support;

use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\StateCasts\BooleanStateCast;
use Filament\Schemas\Components\StateCasts\Contracts\StateCast;

/**
 * The publish switch. It differs from a plain Toggle in one way: the boolean
 * cast has to run before any hydration hook can look at the value, so the
 * only place "absent means on" can be said is the cast itself.
 */
class StatusToggle extends Toggle
{
    /**
     * @return array<StateCast>
     */
    public function getDefaultStateCasts(): array
    {
        return array_map(
            fn (StateCast $cast): StateCast => $cast instanceof BooleanStateCast ? new PublishedStateCast : $cast,
            parent::getDefaultStateCasts(),
        );
    }
}
