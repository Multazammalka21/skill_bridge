<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new parent user.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $validated['password'],
            'role'     => 'parent',
        ]);

        $token = auth('api')->login($user);

        return response()->json([
            'message' => 'Registrasi berhasil.',
            'user'    => $user,
            'token'   => $token,
            'type'    => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ], 201);
    }

    /**
     * Login with email + password — returns JWT Bearer token.
     * Also demonstrates Basic Auth support (credentials in body or header).
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        $token = auth('api')->attempt([
            'email'    => $validated['email'],
            'password' => $validated['password'],
        ]);

        if (! $token) {
            throw ValidationException::withMessages([
                'email' => ['Email atau kata sandi salah.'],
            ]);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Login via HTTP Basic Auth (Authorization: Basic base64(email:password)).
     * Demonstrates the Basic Auth requirement.
     */
    public function loginBasic(Request $request)
    {
        // Basic Auth credentials come from Authorization header
        $email    = $request->getUser();
        $password = $request->getPassword();

        if (! $email || ! $password) {
            return response()->json([
                'message' => 'Basic Auth credentials diperlukan.',
            ], 401)->header('WWW-Authenticate', 'Basic realm="Pinteria API"');
        }

        $token = auth('api')->attempt([
            'email'    => $email,
            'password' => $password,
        ]);

        if (! $token) {
            return response()->json([
                'message' => 'Email atau kata sandi salah.',
            ], 401)->header('WWW-Authenticate', 'Basic realm="Pinteria API"');
        }

        return $this->respondWithToken($token);
    }

    /**
     * Logout — invalidate the current JWT token.
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'message' => 'Logout berhasil. Token telah dibatalkan.',
        ]);
    }

    /**
     * Refresh JWT token — get a new token before the old one expires.
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the authenticated user info.
     */
    public function me()
    {
        $user = auth('api')->user()->load('children');

        return response()->json([
            'user' => $user,
        ]);
    }

    /**
     * Return a standardised JWT response.
     */
    protected function respondWithToken(string $token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60,
            'user'         => auth('api')->user(),
        ]);
    }
}
