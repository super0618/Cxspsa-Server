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

        return response()->json(['token' => $token], 200);
    }

    public function signup(Request $request): JsonResponse
    {
        $credentials = $request->all();

        $user = User::query()->where('email', $credentials['email'])->first();

        if ($user !== null) {
            return response()->json(['message' => 'Email already exists'], 400);
        }

        User::create($credentials);

        return response()->json(['message' => 'User created'], 201);
    }

    public function get(Request $request): JsonResponse
    {
        $token = $request->header('Authorization');

        if ($token === null) {
            return response()->json(['message' => 'Invalid token'], 400);
        }

        try {
            $payload = JWTAuth::setToken($token)->getPayload();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid token'], 400);
        }

        $userid = $payload->get('sub');

        $user = User::query()->where('id', $userid)->first();

        if ($user === null) {
            return response()->json(['message' => 'Invalid User'], 400);
        }

        return response()->json($user, 200);
    }
}
