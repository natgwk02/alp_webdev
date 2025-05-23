<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

// class ProfileController extends Controller
// {
//     public function show()
//     {
//         return view('profile');
//     }

//     public function update(Request $request)
//     {
//         $user = auth()->user();
        
//         $validated = $request->validate([
//             'name' => 'required|string|max:255',
//             'email' => 'required|email|unique:users,email,'.$user->id,
//             'phone' => 'nullable|string|max:20',
//             'birthdate' => 'nullable|date',
//             'profile_photo' => 'nullable|image|max:2048'
//         ]);

//         // Handle profile photo upload
//         if ($request->hasFile('profile_photo')) {
//             // Delete old photo if exists
//             if ($user->profile_photo) {
//                 Storage::delete($user->profile_photo);
//             }
            
//             $path = $request->file('profile_photo')->store('profile-photos');
//             $validated['profile_photo'] = $path;
//         }

//         $user->update($validated);

//         return back()->with('success', 'Profile updated successfully!');
//     }

//     public function updatePassword(Request $request)
//     {
//         $request->validate([
//             'current_password' => 'required|current_password',
//             'new_password' => ['required', 'confirmed', Password::defaults()],
//         ]);

//         auth()->user()->update([
//             'password' => Hash::make($request->new_password)
//         ]);

//         return back()->with('success', 'Password updated successfully!');
//     }
// }

