<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        // event(new MessageEvent('hello world'));

        $email = $request->input('email');
        $password = $request->input('password');

        if (!$email) {
            return response()->json(['message' => 'Email is required'], 400);
        }

        if (!$password) {
            return response()->json(['message' => 'Password is required'], 400);
        }

        $user = User::query()->where('email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid Email'], 400);
        }

        if (!Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Invalid Password'], 400);
        }

        $token = JWTAuth::attempt($request->only('email', 'password'));

        if ($token === false) {
            return response()->json(['message' => 'Invalid Credentials'], 400);
        }

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
