<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     * UPDATED: Redirects to the Login page instead of Dashboard
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * OVERRIDE: Add a success flash message before redirecting.
     */
    protected function verified(\Illuminate\Http\Request $request)
    {
        session()->flash('success', 'Your email has been successfully verified! You can now log in.');
        return redirect($this->redirectPath());
    }
}