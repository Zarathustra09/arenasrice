<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Show the profile page.
     */
    public function index()
    {
        $user = Auth::user();
        return view('admin.profile.index', compact('user'));
    }

    /**
     * Update the profile information.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'profilepicture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('profilepicture')) {
            if ($user->profilepicture) {
                Storage::delete('public/' . $user->profilepicture);
            }
            $path = $request->file('profilepicture')->store('profilepictures', 'public');
            $user->profilepicture = $path;
        }

        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
    }

    public function guestIndex()
    {
        $user = Auth::user();
        return view('guest.profile.index', compact('user'));
    }

    public function guestUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'profilepicture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('profilepicture')) {
            if ($user->profilepicture) {
                Storage::delete('public/' . $user->profilepicture);
            }
            $path = $request->file('profilepicture')->store('profilepictures', 'public');
            $user->profilepicture = $path;
        }

        $user->save();

        return redirect()->route('guest.profile.index')->with('success', 'Profile updated successfully.');
    }


}
