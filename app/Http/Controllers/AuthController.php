<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
{
    try {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(['token' => $token, 'message' => 'Registration successful'], 201);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to register user', 'details' => $e->getMessage()], 500);
    }
}


    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid email or password'], 401);
            }

            return response()->json(['token' => $token, 'message' => 'Login successful'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Login failed', 'details' => $e->getMessage()], 500);
        }
    }
}
