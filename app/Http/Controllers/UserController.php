<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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
        
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid login credentials'], Response::HTTP_UNAUTHORIZED);
        }
        
        // 세션을 생성하고 현재 사용자의 ID를 저장합니다.
        $request->session()->put('user_id', Auth::id());

        return response()->json(['message' => 'User logged in successfully']);
    }

    /**
     * Invalidate the provided JWT token and log the user out.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // 세션에서 사용자의 ID를 제거하여 로그아웃 처리합니다.
        $request->session()->forget('user_id');

        return response()->json(['message' => 'User logged out successfully']);
    }

    public function getAllUsers()
    {
        $users = User::all();
        
        return response()->json(['users' => $users]);
    }
}