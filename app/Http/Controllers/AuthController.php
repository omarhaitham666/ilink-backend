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
            'token' => $data['token']
        ]);
    }

    public function login(LoginRequest $request)
    {
        $data = $this->authService->login($request->validated());

        return response()->json([
            'message' => 'Logged in successfully',
            'user' => $data['user'],
            'token' => $data['token']
        ]);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => $this->authService->me($request->user())
        ]);
    }
}