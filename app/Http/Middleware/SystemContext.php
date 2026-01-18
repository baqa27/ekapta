<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SystemContext
{
    /**
     * Handle an incoming request.
     * Middleware untuk set context sistem (TA atau KP)
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $context = null): Response
    {
        // Set context ke session jika parameter diberikan
        if ($context) {
            session(['ekapta_context' => $context]);
        }

        // Share context ke semua views
        view()->share('currentContext', session('ekapta_context', 'ta'));

        return $next($request);
    }
}
