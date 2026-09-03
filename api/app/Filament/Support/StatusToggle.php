<?php

declare(strict_types=1);

namespace App\Filament\Support;

use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\StateCasts\BooleanStateCast;
use Filament\Schemas\Components\StateCasts\Contracts\StateCast;

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
