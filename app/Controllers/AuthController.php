<?php

namespace App\Controllers;

use Core\Request;
use Core\JwtAuth;
use Core\Response;

class AuthController extends Controller
{
    public function login(Request $request): void
    {
        $data = $request->body();

        $email    = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            Response::validationError([
                'email'    => !$email    ? 'Email is required'    : null,
                'password' => !$password ? 'Password is required' : null,
            ]);
        }

        // TODO: replace with real DB lookup
        // $user = User::where('email', $email)[0] ?? null;
        // if (!$user || !password_verify($password, $user['password'])) { ... }

        // Example: generate token for authenticated user
        $token = JwtAuth::generate([
            'user_id' => 1,
            'email'   => $email,
            'role'    => 'admin',
        ]);

        $this->success([
            'token'      => $token,
            'expires_in' => (int) env('JWT_EXPIRY', 3600),
        ], 'Login successful');
    }
}
