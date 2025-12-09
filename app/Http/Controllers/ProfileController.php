<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    // Show the edit profile form
    public function edit()
    {
        $user = User::first(); // Ambil user pertama untuk demo

        if (!$user) {
            return redirect('/')->with('error', 'User not found');
        }

        return view('profile.edit', compact('user'));
    }

    // Show the user's profile
    public function show()
    {
        $user = User::first(); // Ambil user pertama untuk demo

        if (!$user) {
            return redirect('/')->with('error', 'User not found');
        }

        return view('profile.show', compact('user'));
    }

    // Update the user's profile picture
    public function update(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::first();

        if (!$user) {
            return redirect('/')->with('error', 'User not found');
        }

        // Delete the old profile picture if it exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Store the new profile picture
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');
        $user->profile_picture = $path;
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile picture updated successfully!');
    }

    // Delete the user's profile picture
    public function destroy()
    {
        $user = User::first();

        if (!$user) {
            return redirect('/')->with('error', 'User not found');
        }

        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
            $user->profile_picture = null;
            $user->save();
        }

        return redirect()->route('profile.edit')->with('success', 'Profile picture deleted successfully!');
    }
}
