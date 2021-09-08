<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SetHeaders
{
    /**
     * Handle an incoming request and sets headers
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Converts
        $request->headers->set('Accept', 'application/json');
        return $next($request);
    }
}