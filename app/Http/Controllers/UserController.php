<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $type_menu = 'user';

        $keyword = trim($request->input('name'));
        $role = $request->input('role');

        $users = User::when($keyword, function ($query, $name) {
            $query->where('name', 'like', '%' . $name . '%');
        })
            ->when($role, function ($query, $role) {
                $query->where('role', $role);
            })
            ->latest()
            ->paginate(10);

        $users->appends(['name' => $keyword, 'role' => $role]);

        return view('pages.users.index', compact('type_menu', 'users'));
    }
    public function create()
    {
        $type_menu = 'user';

        // arahkan ke file pages/users/create.blade.php
        return view('pages.users.create', compact('type_menu'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif',
        ]);
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move('img/user/', $imagePath);
        }
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'],
            'image' => $imagePath, 
        ]);
        return Redirect::route('user.index')->with('success', 'User'. $user->name .'berhasil di tambah.');
    }
    public function edit(User $user)
    {
        $type_menu = 'user';

        return view('pages.users.edit', compact('user', 'type_menu'));
    }
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id, 
            'password' => 'nullable|min:8', 
            'role' => 'required',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if (!empty($request->password)) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move('img/user/', $path);
            $user->update([
                'image' => $path
            ]);
        }
        return Redirect::route('user.index')->with('success', 'User dengan Nama'. $user->name .'berhasil di ubah.');
    }
    public function destroy(User $user)
    {
        $user->delete();
        return Redirect::route('user.index')->with('success', 'User dengan Nama'.$user->name .'berhasil di hapus.');
    }
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif',
        ]);
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move('img/user/', $imagePath);
        }
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'],
            'image' => $imagePath,
        ]);

        return Redirect::route('register')->with('success', 'User'. $user->name .'berhasil di tambah.');
    }
}
