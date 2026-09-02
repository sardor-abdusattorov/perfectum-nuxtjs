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
        $locales = app_locales();

        $locale = $request->input('locale')
            ?? $request->input('lang')
            ?? $request->header('X-Locale')
            ?? $request->getPreferredLanguage($locales);

        /**
         * The fallback rather than `app.locale`: setting the locale writes
         * into that key, so in a long-lived process the default would become
         * whatever the previous request asked for.
         */
        app()->setLocale(
            in_array($locale, $locales, true) ? $locale : config('app.fallback_locale')
        );

        return $next($request);
    }
}
