<?php

namespace App\Http\Middleware;

use Closure;

class PreventRequestsDuringMaintenance
{
    /**
     * Handle an incoming request.
     *
     * This override disables Laravel's maintenance mode redirect
     * to avoid the fatal error related to the missing 'redirect' binding.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Completely bypass maintenance logic
        return $next($request);
    }
}
