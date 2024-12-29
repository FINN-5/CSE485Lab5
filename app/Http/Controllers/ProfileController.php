<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // public function update(Request $request)
    // {
    //     $user = Auth::user();

    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
    //         'bio' => 'nullable|string',
    //         'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);
    //     $user->name = $request->name;
    //     $user->email = $request->email;
    //     $user->bio = $request->bio;

    //     if ($request->hasFile('avatar')) {
    //         // Xóa ảnh cũ nếu có
    //         if ($user->avatar) {
    //             Storage::delete($user->avatar);
    //         }

    //         // Lưu ảnh mới
    //         $path = $request->file('avatar')->store('avatars', 'public');
    //         $user->avatar = $path;
    //     }

    //     $user->save();

    //     return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    // }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }


        if ($request->hasFile('avatar')) {
            // Xóa avatar cũ nếu có
            if ($request->user()->avatar) {
                Storage::delete($request->user()->avatar);
            }

            // Lưu avatar mới
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $request->user()->avatar = $avatarPath;
            // $user->save();
        }
        $request->user()->save();

        //return Redirect::route('profile.edit')->with('status', 'profile-updated');
        return Redirect::route('profile.edit')->with([
            'status' => 'profile-updated',
            'user' => $request->user()
        ]);
    }
}
