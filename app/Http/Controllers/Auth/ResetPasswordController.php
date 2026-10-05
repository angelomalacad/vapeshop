<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * ✅ OVERRIDE: Get the password validation rules.
     * Enforces: min 8 chars, 1 uppercase, 1 number, 1 special char.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'string',
                'min:8',                  // Minimum 8 characters
                'confirmed',              // Must match password_confirmation
                'regex:/[A-Z]/',          // At least one uppercase letter
                'regex:/[0-9]/',          // At least one number
                'regex:/[!@#$%^&*(),.?":{}|<>_\-+=\[\]\\\\\/;\'`~]/', // At least one special character
            ],
        ];
    }

    /**
     * ✅ OVERRIDE: Custom validation messages.
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [
            'password.min' => 'Password must be at least 8 characters long.',
            'password.regex' => 'Password must contain at least one uppercase letter, one number, and one special character.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }
}