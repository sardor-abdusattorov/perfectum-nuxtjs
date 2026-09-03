<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
