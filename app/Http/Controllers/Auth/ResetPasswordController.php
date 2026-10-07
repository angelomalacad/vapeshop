<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/login';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[!@#$%^&*(),.?":{}|<>_\-+=\[\]\\\\\/;\'`~]/',
            ],
        ];
    }

    protected function validationErrorMessages()
    {
        return [
            'password.min' => 'Password must be at least 8 characters long.',
            'password.regex' => 'Password must contain at least one uppercase letter, one number, and one special character.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }

    /**
     * ✅ COMPLETELY CUSTOM RESET — no trait magic, full control
     */
    public function reset(Request $request)
    {
        // STEP 1 — validate basic rules
        $request->validate($this->rules(), $this->validationErrorMessages());

        // STEP 2 — find user
        $user = \App\Models\User::where('email', $request->email)->first();

        // STEP 3 — HARD BLOCK if user not found (forces error to show)
        if (!$user) {
            return back()->withErrors(['email' => 'No account found with that email.']);
        }

        // STEP 4 — HARD BLOCK if same password
        if (Hash::check($request->password, $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['password' => 'SAME PASSWORD DETECTED — you cannot reuse your old password.']);
        }

        // STEP 5 — update password
        $user->password = Hash::make($request->password);
        $user->setRememberToken(\Illuminate\Support\Str::random(60));
        $user->save();

        return redirect($this->redirectTo)->with('status', 'Your password has been reset successfully.');
    }
}
