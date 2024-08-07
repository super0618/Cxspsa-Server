<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        // event(new MessageEvent('hello world'));

        $email = $request->input('email');
        $password = $request->input('password');

        if (!$email) {
            return response()->json(['error' => 'Email is required'], 400);
        }

        if (!$password) {
            return response()->json(['error' => 'Password is required'], 400);
        }

        $user = User::query()->where('email', $email)->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid Email'], 400);
        }

        if ($password != $user->password) {
            return response()->json(['error' => 'Invalid Password'], 400);
        }

        $token = Auth::attempt([
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        return response()->json($token);
    }

    public function signup(): JsonResponse
    {
        return response()->json();
    }

    public function get(): JsonResponse
    {
        return response()->json(User::user());
    }
}
