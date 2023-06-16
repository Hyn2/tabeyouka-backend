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

        $sessionId = $request->session()->getId();

        $sessionData = $request->session()->all();

        // 세션 리-지너레이션 (고정 공격 방지)
        $request->session()->regenerate();

        return response()->json(['message' => 'User logged in successfully'])
        ->withCookie(cookie(
            'laravel_session',
            $request->session()->getId(),
            config('session.lifetime'),
            config('session.path'),
            config('session.domain'),
            config('session.secure'),
            config('session.http_only'),
            config('session.same_site'),
        ))
        ->header('Access-Control-Allow-Credentials', 'true');
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

        Auth::logout();

        // 세션을 무효화합니다.
        $request->session()->invalidate();

        // 세션 쿠키를 재생성합니다.
        $request->session()->regenerateToken();

        return response()->json(['message' => 'User logged out successfully']);
    }

    public function getAllUsers()
    {
        $users = User::all();
        return response()->json(['users' => $users]);
    }

    /**
     * Check whether a user is logged in or not.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLoginStatus(Request $request)
    {
        // 사용자가 로그인했는지 여부를 판단합니다.
        $isLoggedIn = $request->session()->has('user_id');

        if ($isLoggedIn) {
            // 로그인한 경우, 로그인 상태 메시지와 함께 사용자 정보를 반환합니다.
            $userId = $request->session()->get('user_id');
            $user = User::find($userId);
            $response = response()->json([
                'message' => 'User is logged in',
                'user' => $user,
            ]);
        } else {
            // 로그인하지 않은 경우, 로그아웃 상태 메시지를 반환합니다.
            $response = response()->json(['message' => 'User is logged out']);
        }
    
        if ($isLoggedIn) {
            $response->withCookie(cookie(
                'laravel_session',
                $request->session()->getId(),
                config('session.lifetime'),
                config('session.path'),
                config('session.domain'),
                config('session.secure'),
                config('session.http_only'),
                config('session.same_site'),
            ))
            ->header('Access-Control-Allow-Credentials', 'true');
        }
    
        return $response;
    }
}