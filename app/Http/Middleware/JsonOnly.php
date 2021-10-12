<?php

declare(strict_types=1);

namespace App\Http\Middleware;

class JsonOnly
{
    public function handle($request, \Closure $next)
    {
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}