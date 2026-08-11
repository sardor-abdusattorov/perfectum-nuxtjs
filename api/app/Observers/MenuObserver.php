<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Menu;

class MenuObserver
{
    public function saved(Menu $menu): void
    {
        clear_menus_cache();
    }

    public function deleted(Menu $menu): void
    {
        clear_menus_cache();
    }
}
