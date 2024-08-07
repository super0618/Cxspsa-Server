<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        // event(new MessageEvent('hello world'));

        $email = $request->input('email');
        $password = $request->input('password');

        if ($email === null) {
            return response()->json(['message' => 'Email is required'], 400);
        }

        if ($password === null) {
            return response()->json(['message' => 'Password is required'], 400);
        }

        $user = User::query()->where('email', $email)->first();

        if ($user === null) {
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

    public function get(Request $request): JsonResponse
    {
        $token = $request->header('Authorization');

        if ($token === null) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $payload = JWTAuth::setToken($token)->getPayload();

        $claims = $payload->toArray();

        return response()->json($claims);
    }
}
