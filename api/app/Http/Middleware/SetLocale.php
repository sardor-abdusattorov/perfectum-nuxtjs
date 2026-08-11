<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locales = config('app.locales', [config('app.locale')]);

        $locale = $request->query('locale')
            ?? $request->header('X-Locale')
            ?? $request->getPreferredLanguage($locales);

        app()->setLocale(
            in_array($locale, $locales, true) ? $locale : config('app.locale')
        );

        return $next($request);
    }
}
