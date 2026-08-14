<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The API answers the site alone. The feeds the old site published — the legal
 * documents and the coverage map — are read from elsewhere, so they are opened
 * back up here: the CORS middleware is the outermost one, so a route-level
 * middleware could never outlive its header.
 */
class HandleApiCors extends HandleCors
{
    public function handle($request, Closure $next): Response
    {
        $response = parent::handle($request, $next);

        if ($request->headers->has('Origin') && $this->isPublicFeed($request)) {
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->remove('Access-Control-Allow-Credentials');
        }

        return $response;
    }

    private function isPublicFeed(Request $request): bool
    {
        return $request->is(...(array) config('cors.public_paths', []));
    }
}
