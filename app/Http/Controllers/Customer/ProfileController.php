<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('customer.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'gender' => ['nullable', 'string', 'in:male,female,prefer_not_to_say'],
            'birthdate' => ['nullable', 'date', 'before:today'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^(09|\+639)\d{9}$/'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ], [
            'phone.regex' => 'Please enter a valid Philippine mobile number (e.g., 09123456789)',
            'email.unique' => 'That email is already registered to another account.',
            'profile_picture.image' => 'The file must be an image (JPG, PNG, WEBP, GIF).',
            'profile_picture.max' => 'The image must be smaller than 5 MB.',
        ]);

        // ✅ Make sure the new email isn't already pending verification on another account
        if (isset($validated['email'])) {
            $pendingTaken = \App\Models\User::where('pending_email', $validated['email'])
                ->where('id', '!=', $user->id)
                ->exists();
            if ($pendingTaken) {
                return back()->withErrors(['email' => 'That email is already pending verification on another account.'])->withInput();
            }
        }

        // ✅ Detect email change (case-insensitive)
        $emailChanged = isset($validated['email'])
            && strtolower($validated['email']) !== strtolower($user->email);

        if ($emailChanged) {
            // ✅ DON'T change email yet — queue it as pending
            $user->pending_email = $validated['email'];
            unset($validated['email']);
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $validated['profile_picture'] = $path;
        } else {
            unset($validated['profile_picture']);
        }

        $user->fill($validated);
        $user->save();

        // ✅ If email changed, send verification to the pending email and redirect to verify page
        if ($emailChanged) {
            $user->sendEmailVerificationNotification();

            return redirect()->route('verification.notice')
                ->with('success', 'Email change requested! A verification link has been sent to ' . $user->pending_email . '. Your current email still works until you verify.');
        }

        return redirect()->route('customer.profile.index')
            ->with('success', 'Profile updated successfully!');
    }

    public function resendVerification(Request $request)
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail() && !$user->pending_email) {
            return back()->with('info', 'Your email is already verified.');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Verification email sent! Please check your inbox.');
    }

    /**
     * Cancel a pending email change.
     */
    public function cancelPendingEmail(Request $request)
    {
        $user = Auth::user();

        if (!$user->pending_email) {
            return back()->with('info', 'No pending email change to cancel.');
        }

        $user->update(['pending_email' => null]);

        return redirect()->route('customer.profile.index')
            ->with('success', 'Pending email change cancelled.');
    }
}