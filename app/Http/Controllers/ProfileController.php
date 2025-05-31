<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{


    //   Show the user profile
    public function show()
    {
        return view('profile.show', [
            'user' => Auth::user(),
        ]);
    }

    //    Show the edit profile form
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    //    Update the user profile.
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        // Check if email changed
        $emailChanged = $user->email !== $request->email;

        // Update user profile
        $user->name = $request->name;
        if ($emailChanged) {
            $user->email = $request->email;
            $user->email_verified_at = now(); // Auto-verify the new email
        }
        $user->save();

        return redirect()->route('profile.show')
            ->with('success', 'Your profile has been updated successfully.');
    }

    // Show the change password form.
    public function changePasswordForm()
    {
        return view('profile.change-password');
    }

    //    Show the change password form.
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.show')
            ->with('status', 'Your password has been changed successfully.');
    }
}
