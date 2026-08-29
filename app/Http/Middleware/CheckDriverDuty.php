<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DriverShift;

class CheckDriverDuty
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Allow if user is not a driver (for other roles)
        if (Auth::user()->role !== 'driver') {
            return $next($request);
        }

        // Check if driver has an active shift today
        $todayShift = DriverShift::where('shift_date', today())
            ->where('status', 'active')
            ->where('driver_id', Auth::id())
            ->first();

        // If NO active shift (OFF DUTY)
        if (!$todayShift) {
            // ✅ ALLOW: Dashboard (shows recent activity)
            if ($request->routeIs('driver.dashboard')) {
                return $next($request);
            }

            // ✅ ALLOW: Logout
            if ($request->routeIs('logout')) {
                return $next($request);
            }

            // ❌ BLOCK: All other driver pages
            return redirect()->route('driver.dashboard')
                ->with('error', 'You are off duty today. You can only view the Dashboard.');
        }

        // ✅ ON DUTY: Allow everything
        return $next($request);
    }
}