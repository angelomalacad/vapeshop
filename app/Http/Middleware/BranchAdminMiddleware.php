<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SymfonyComponent\HttpFoundation\Response;

class BranchAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $user = Auth::user();
        if (!in_array($user->role, ['branch_admin', 'super_admin'])) {
            return redirect()->route('home')->with('error', 'Access denied. Branch admin access required.');
        }

        return $next($request);
    }
}