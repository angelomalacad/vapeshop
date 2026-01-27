<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        // Check if user is customer (or admin for testing)
        $user = Auth::user();
        if (!in_array($user->role, ['customer', 'super_admin', 'branch_admin', 'staff'])) {
            return redirect()->route('home')->with('error', 'Unauthorized access. Customer area only.');
        }

        return $next($request);
    }
}