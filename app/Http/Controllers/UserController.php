<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    /**
     * Register a new user with the provided email, password, and nickname.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
    
        return response()->json(['message' => 'User registered successfully'], Response::HTTP_CREATED);
    }

    /**
     * Authenticate a user with the provided email and password.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
    
        $credentials = $request->only('email', 'password');
        
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid login credentials'], Response::HTTP_UNAUTHORIZED);
        }
    
        return response()->json([
            'token' => $token,
            'expires' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Invalidate the provided JWT token and log the user out.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
{
    try {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'User logged out successfully']);
    } catch (JWTException $e) {
        return response()->json(['message' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
    }
}

    /**
     * Refresh the provided JWT token and return a new token with an updated expiration.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken(Request $request)
    {
        try {
            $token = JWTAuth::parseToken();
            $newToken = $token->refresh();
            return response()->json([
                'token' => $newToken,
                'expires' => JWTAuth::factory()->getTTL() * 60,
            ], Response::HTTP_OK);
        } catch (JWTException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }
}