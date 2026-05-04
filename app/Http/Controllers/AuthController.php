<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function register(RegisterRequest $request)
    {
        $data = $this->authService->register($request->validated());

        return response()->json([
            'message' => 'Registered successfully',
            'user' => $data['user'],
            'access_token' => $data['access_token'],
        ])->cookie(
            'refresh_token',
            $data['refresh_token'],
            60 * 24 * 7, // 7 days
            '/',
            null,
            false, // خليها true لما تستخدم https
            true,  // HttpOnly 🔥
            false,
            'Strict'
        );
    }

    public function login(LoginRequest $request)
    {
        $data = $this->authService->login($request->validated());

        if (!$data) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return response()->json([
            'message' => 'Logged in successfully',
            'user' => $data['user'],
            'access_token' => $data['access_token'],
        ])->cookie(
            'refresh_token',
            $data['refresh_token'],
            60 * 24 * 7,
            '/',
            null,
            false,
            true,
            false,
            'Strict'
        );
    }

    public function refresh(Request $request)
    {
        $data = $this->authService->refresh($request);

        if (!$data) {
            return response()->json(['message' => 'Invalid refresh token'], 401);
        }

        return response()->json([
            'access_token' => $data['access_token']
        ]);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request);

        return response()->json([
            'message' => 'Logged out successfully'
        ])->cookie('refresh_token', '', -1);
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }
}