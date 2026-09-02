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
 * path on the main domain, so the admin subdomain serves the panel alone and
 * the public API isn't duplicated for search engines. A read goes with 301;
 * anything else with 308, which a client follows without turning the request
 * into a GET and dropping its body.
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
                $request->isMethodSafe() ? 301 : 308,
            );
        }

        return $next($request);
    }
}
