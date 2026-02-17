<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class HandleAppearance
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Share appearance variable with all views (default to 'light' if not set)
        View::share('appearance', $request->cookie('appearance') ?? 'light');
        
        return $next($request);
    }
}