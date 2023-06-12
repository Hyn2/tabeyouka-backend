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

    /**
     * Retrieve the currently logged in user's information.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthenticatedUser(Request $request)
    {
        // 세션에서 현재 사용자 ID를 가져옵니다.
        $userId = $request->session()->get('user_id');

        // 만약 사용자 ID가 세션에 없는 경우, 로그인하지 않은 상태로 판단합니다.
        if (!$userId) {
            return response()->json(['message' => 'Not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        // 사용자 ID로 유저를 찾습니다.
        $user = User::find($userId);

        // 찾은 유저 정보를 반환합니다.
        return response()->json(['user' => $user]);
    }

    /**
     * Check whether a user is logged in or not.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function isLoggedIn(Request $request)
    {
        // 사용자가 로그인했는지 여부를 판단합니다.
        $isLoggedIn = $request->session()->has('user_id');

        // 로그인한 경우, 로그인 상태 메시지를 반환합니다.
        if ($isLoggedIn) {
            return response()->json(['message' => 'User is logged in']);
        }

        // 로그인하지 않은 경우, 로그아웃 상태 메시지를 반환합니다.
        return response()->json(['message' => 'User is logged out']);
    }
}