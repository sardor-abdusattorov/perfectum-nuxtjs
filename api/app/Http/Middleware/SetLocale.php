<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Названную локаль отдаём из контентных: приложение может читать английский
     * задолго до того, как сайт откроет раздел /en/. А вот угадывать по браузеру
     * можно только среди языков сайта — контентный список шире, и
     * getPreferredLanguage вернёт любое совпадение из него, так что английский
     * браузер получил бы язык, на котором ещё нет ни страниц, ни карты сайта.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locales = app_locales();

        $locale = $request->input('locale')
            ?? $request->input('lang')
            ?? $request->header('X-Locale')
            ?? $request->getPreferredLanguage(site_locales());

        app()->setLocale(
            in_array($locale, $locales, true) ? $locale : config('app.fallback_locale')
        );

        return $next($request);
    }
}
