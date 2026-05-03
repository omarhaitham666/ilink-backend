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

    // 👇 الحل الحقيقي

        Auth::login($user); // 👈 فقط ده
    session()->regenerate();

    return $user;
}
    public function login(array $data)
    {
        if (!Auth::attempt($data)) {
            return null;
        }

        return Auth::user();
    }

    public function logout()
    {
        Auth::logout();
    }

    public function me()
    {
        return Auth::user();
    }
}