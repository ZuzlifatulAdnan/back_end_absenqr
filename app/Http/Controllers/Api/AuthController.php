<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            // UBAH BAGIAN INI
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials are incorrect.'
            ], 401); // 401 Unauthorized
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,                      // Tambahkan ini
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
        ]);
    }
}
