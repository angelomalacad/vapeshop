<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BranchAdminMiddleware
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

        // Check if user is branch admin or super admin
        $user = Auth::user();
        if (!in_array($user->role, ['branch_admin', 'super_admin'])) {
            // If not authorized, redirect based on role
            if ($user->role === 'customer') {
                return redirect()->route('customer.dashboard')->with('error', 'Access denied. Admin access required.');
            } else {
                return redirect()->route('home')->with('error', 'Unauthorized access.');
            }
        }

        // Additional check: branch admin must have a branch assigned
        if ($user->role === 'branch_admin' && !$user->branch_id) {
            return redirect()->route('home')->with('error', 'Your account is not assigned to any branch.');
        }

        return $next($request);
    }
}