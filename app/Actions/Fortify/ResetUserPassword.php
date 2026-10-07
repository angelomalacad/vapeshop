<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  array<string, string>  $input
     */
    public function reset(User $user, array $input): void
    {
        Validator::make($input, [
            'password' => $this->passwordRules(),
        ], [
            'password.min' => 'Password must be at least 8 characters long.',
            'password.regex' => 'Password must contain at least one uppercase letter, one number, and one special character.',
            'password.confirmed' => 'Password confirmation does not match.',
        ])->validate();

        // ✅ Block reusing the current password
        if (Hash::check($input['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'Your new password cannot be the same as your current password.',
            ]);
        }

        $user->forceFill([
            'password' => $input['password'],
        ])->save();
    }
}
