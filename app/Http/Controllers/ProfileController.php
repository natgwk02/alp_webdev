<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the user's profile page
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to view this page.');
        }

        return view('customer.profile', compact('user'));
    }

    /**
     * Update the user's profile information
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
            'users_name'     => 'required|string|max:255',
            'users_email'    => [
                'required',
                'email',
                Rule::unique('users', 'users_email')->ignore($user->getKey(), 'users_id'),
            ],
            'users_phone'    => 'nullable|string|max:20',
            'users_address'  => 'nullable|string|max:255',
            'profile_photo'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $validated['profile_photo'] = $path;
        }

        $user->users_name = $validated['users_name'];
        $user->users_email = $validated['users_email'];
        $user->users_phone = $validated['users_phone'] ?? null;
        $user->users_address = $validated['users_address'] ?? null;

        if (isset($validated['profile_photo'])) {
            $user->profile_photo = $validated['profile_photo'];
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
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
