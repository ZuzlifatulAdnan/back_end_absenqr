<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $type_menu = 'profile';
        $user = Auth::user();
        return view('pages.profile.index', compact('type_menu', 'user', ));
    }
    public function edit()
    {
        $type_menu = 'profile';
        return view('pages.profile.edit', compact('type_menu'));
    }
    public function update(Request $request, User $user)
    {
        $image = $request->file('file');

        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'file' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_handphone' => $request->no_handphone,
        ]);

        if ($image) {

            $path = time() . '.' . $image->getClientOriginalExtension();
            $image->move('img/user/', $path);

            if ($user->foto) {
                $oldImagePath = 'img/user/' . $user->foto;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $user->update([
                'image' => $path
            ]);
        }

        return redirect()->route('profile.index')->with('success', 'Data Akun '.$user->name . ' berhasil diperbarui.');
    }
    public function changePasswordForm()
    {
        $type_menu = 'profile';
        return view('pages.profile.change-password', compact('type_menu'));
    }
    public function changePassword(Request $request, User $user)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Update the new password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('profile.index')->with('success', 'password Akun '.$user->name.' berhasil diperbarui.');
    }
}
