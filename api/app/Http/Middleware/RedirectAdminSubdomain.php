<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The public routes carry no domain constraint, so they also resolve on the
 * Filament admin subdomain (admin.example.com/api/v1/news). This middleware,
 * applied to the public route group only, sends those requests to the same
 * path on the main domain with a 301, so the admin subdomain serves the panel
 * alone and the public API isn't duplicated for search engines.
 */
class RedirectAdminSubdomain
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();

        if (str_starts_with($host, 'admin.')) {
            $mainHost = substr($host, strlen('admin.'));

            return redirect()->away(
                $request->getScheme().'://'.$mainHost.$request->getRequestUri(),
                301,
            );
        }

        return $next($request);
    }
}
