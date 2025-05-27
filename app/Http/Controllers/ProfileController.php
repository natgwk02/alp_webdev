<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Menampilkan halaman profil user
    public function show()
    {
        // Hapus atau komentari baris di bawah ini jika ada
        // if (!Auth::check()) {
        //     return redirect()->route('login');
        // }

        return view('customer.profile');
    }

    // Mengupdate informasi profil
    public function update(Request $request)
{
    $user = Auth::user();

    $validated = $request->validate([
        'users_name'    => 'required|string|max:255',
        'email'         => 'required|email|unique:users,users_email,' . $user->users_id . ',users_id',
        'phone'         => 'nullable|string|max:20',
        'birthdate'     => 'nullable|date',
        'address'       => 'nullable|string|max:255',
        'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Jika ada file baru, upload dan simpan path-nya
    if ($request->hasFile('profile_photo')) {
        // Hapus foto lama jika ada
        if ($user->profile_photo && Storage::exists($user->profile_photo)) {
            Storage::delete($user->profile_photo);
        }

        // Simpan foto baru
        $path = $request->file('profile_photo')->store('profile_photos', 'public');
        $validated['profile_photo'] = $path;
    }

    // Update user
    $user->update($validated);

    return redirect()->route('profile')->with('success', 'Profile berhasil diperbarui.');

    }

    // Mengupdate password user
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || !Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully!');
    }
}
