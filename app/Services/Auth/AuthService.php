<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
 public function register(array $data)
{
    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'age' => $data['age'],
        'gender' => $data['gender'],
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return [
        'user' => $user,
        'token' => $token,
    ];
}

public function login(array $data)
{
    $user = User::where('email', $data['email'])->first();

    if (!$user || !Hash::check($data['password'], $user->password)) {
        return null;
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return [
        'user' => $user,
        'token' => $token,
    ];
}
public function logout(User $user)
{
    $user->tokens()->delete();
}

public function me(User $user)
{
    return $user;
    return auth()->user();
} 

}