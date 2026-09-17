<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $r)
    {
        $d = $r->validate(['name' => 'required|string|max:255', 'email' => 'required|email|unique:users', 'password' => 'required|string|min:8|confirmed']);
        $u = User::create(['name' => $d['name'], 'email' => $d['email'], 'password' => Hash::make($d['password'])]);

        return response()->json(['user' => $u, 'token' => $u->createToken('api')->plainTextToken], 201);
    }

    public function login(Request $r)
    {
        $d = $r->validate(['email' => 'required|email', 'password' => 'required']);
        $u = User::where('email', $d['email'])->first();
        if (! $u || ! Hash::check($d['password'], $u->password)) {
            return response()->json(['message' => 'Invalid credentials'], 422);
        }

        return ['user' => $u, 'token' => $u->createToken('api')->plainTextToken];
    }

    public function logout(Request $r)
    {
        $r->user()->currentAccessToken()->delete();

        return response()->noContent();
    }

    public function forgot(Request $r)
    {
        $r->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($r->only('email'));

        return response()->json(['message' => __($status)], $status === Password::RESET_LINK_SENT ? 200 : 422);
    }

    public function reset(Request $r)
    {
        $d = $r->validate(['token' => 'required', 'email' => 'required|email', 'password' => 'required|min:8|confirmed']);
        $status = Password::reset($d, function ($u, $password) {
            $u->forceFill(['password' => Hash::make($password), 'remember_token' => Str::random(60)])->save();
            $u->tokens()->delete();
        });

        return response()->json(['message' => __($status)], $status === Password::PASSWORD_RESET ? 200 : 422);
    }
}
