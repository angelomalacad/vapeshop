<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Check if user is logged in
        if (Auth::check()) {
            $user = Auth::user();
            
            // Redirect based on role
            switch ($user->role) {
                case 'super_admin':
                    return redirect()->route('admin.dashboard');
                case 'branch_admin':
                    return redirect()->route('branch-admin.dashboard');
                case 'staff':
                    return redirect()->route('staff.dashboard');
                case 'customer':
                    return redirect()->route('customer.dashboard');
                default:
                    return view('home');
            }
        }
        
        // Show landing page for guests
        return view('home');
    }
    
    /**
     * Show about page.
     */
    public function about()
    {
        return view('about');
    }
    
    /**
     * Show contact page.
     */
    public function contact()
    {
        return view('contact');
    }
}