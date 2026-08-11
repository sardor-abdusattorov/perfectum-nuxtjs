<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Social;

class SocialObserver
{
    public function saved(Social $social): void
    {
        clear_socials_cache();
    }

    public function deleted(Social $social): void
    {
        clear_socials_cache();
    }
}
