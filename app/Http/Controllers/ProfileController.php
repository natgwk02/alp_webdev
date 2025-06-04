<?php

namespace App\Http\Controllers;

use App\Models\User; // Ensure this is your User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule; // For more complex validation rules

class ProfileController extends Controller
{
    /**
     * Display the user's profile page.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // If for some reason Auth::user() could be null and not caught by middleware
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to view this page.');
        }

        return view('customer.profile', compact('user'));
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Authentication session expired. Please log in again.');
        }

        $validated = $request->validate([
            'users_name'    => 'required|string|max:255',
            // Use Rule::unique to make it cleaner, especially with primary key considerations
            'users_email'   => [
                'required',
                'email',
                Rule::unique('users', 'users_email')->ignore($user->{$user->getKeyName()}, $user->getKeyName()),
            ],
            'phone'         => 'nullable|string|max:20',
            'birthdate'     => 'nullable|date',
            'address'       => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // 2MB max
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if it exists
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            // Store new photo in 'public/profile_photos' directory
            // The path stored will be 'profile_photos/filename.ext'
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $validated['profile_photo'] = $path;
        }

        // The line `$validated['users_email'] = $request->users_email;` was redundant
        // as $request->validate() already includes validated 'users_email'.

        // Update user attributes
        $user->update($validated);

        return redirect()->route('profile')->with('success', 'Profile berhasil diperbarui.');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password'     => ['required', 'string', 'min:8', 'confirmed'], // 'confirmed' checks for 'new_password_confirmation' field
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Authentication session expired. Please log in again.');
        }

        // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Kata sandi saat ini salah.']);
        }

        // Update to the new password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profile')->with('success', 'Kata sandi berhasil diperbarui!');
    }
}
