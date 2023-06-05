<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    // Register user
    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'nickname' => 'required|unique:users,nickname',
        ]);

        $user = new User();
        $user->email = $validated['email'];
        $user->password = bcrypt($validated['password']);
        $user->nickname = $validated['nickname'];
        $user->save();

        return response()->json(['message' => 'User created successfully']);
    }

    // Login user
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (auth()->attempt($validated)) {
            $user = auth()->user();

            return response()->json([
                'user' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken
            ]);
        } else {
            return response()->json(['message' => 'Invalid login credentials'], 401);
        }
    }

    // Logout user
    public function logout(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();

        return response()->json(['message' => 'User logged out successfully']);
    }

    // refresh Token
    public function refreshToken(Request $request) {
        try {
            $newToken = Auth::refresh();
            return response()->json(['token' => $newToken], 200);
        } catch (JWTException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
}
