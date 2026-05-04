<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService
{
    // ✅ Register
    public function register(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'age' => $data['age'],
            'gender' => $data['gender'],
        ]);

        return $this->generateTokens($user);
    }

    // ✅ Login
    public function login(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return null;
        }

        return $this->generateTokens($user);
    }

    // 🔐 Generate Access + Refresh Tokens
    private function generateTokens(User $user)
    {
        // (اختياري) امسح التوكنز القديمة
        $user->tokens()->delete();

        // Access Token
        $accessToken = $user->createToken('access')->plainTextToken;

        // Refresh Token
        $refreshToken = $user->createToken('refresh')->plainTextToken;

        return [
            'user' => $user,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ];
    }

    // 🔄 Refresh Access Token
    public function refresh(Request $request)
    {
        $refreshToken = $request->cookie('refresh_token');

        if (!$refreshToken) return null;

        $token = PersonalAccessToken::findToken($refreshToken);

        if (!$token || $token->name !== 'refresh') {
            return null;
        }

        $user = $token->tokenable;

        // امسح access tokens القديمة بس
        $user->tokens()->where('name', 'access')->delete();

        $newAccessToken = $user->createToken('access')->plainTextToken;

        return [
            'access_token' => $newAccessToken
        ];
    }

    // 🚪 Logout
    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->tokens()->delete();
        }
    }
}