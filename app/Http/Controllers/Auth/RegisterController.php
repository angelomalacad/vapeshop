<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/customer/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^(09|\+639)\d{9}$/'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'zip_code' => ['nullable', 'string', 'max:10'],
            'birthdate' => ['nullable', 'date', 'before:'.now()->subYears(18)->format('Y-m-d')],
            'gender' => ['nullable', 'in:male,female,other,prefer_not_to_say'],
            'terms' => ['required', 'accepted'],
        ], [
            'phone.regex' => 'Please enter a valid Philippine mobile number (e.g., 09123456789)',
            'birthdate.before' => 'You must be at least 18 years old to register',
            'terms.accepted' => 'You must agree to the terms and conditions',
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'customer',
            'phone' => $data['phone'],
            'address' => $data['address'],
            'city' => $data['city'] ?? 'Calamba',
            'province' => $data['province'] ?? 'Laguna',
            'zip_code' => $data['zip_code'] ?? null,
            'birthdate' => $data['birthdate'] ?? null,
            'gender' => $data['gender'] ?? null,
            'receive_notifications' => $data['newsletter'] ?? true,
            'receive_promotions' => $data['newsletter'] ?? true,
            'is_active' => true,
        ]);
    }
}