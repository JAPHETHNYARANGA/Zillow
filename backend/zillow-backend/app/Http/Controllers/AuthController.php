<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:buyer,seller,agent',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'profile_image' => $profileImagePath,
            'status' => 'active',
        ]);

        $role = Role::where('name', $validated['role'])->firstOrFail();
        $user->roles()->attach($role);

        $token = $user->createToken('auth_token')->plainTextToken; // Sanctum token

        return response()->json([
            'user' => $user->load('roles'),
            'access_token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->status !== 'active') {
                return response()->json(['message' => 'Account is inactive or suspended'], 403);
            }

            $user->update(['last_login_at' => now()]);

            $token = $user->createToken('auth_token')->plainTextToken; // Sanctum token

            return response()->json([
                'user' => $user->load('roles'),
                'access_token' => $token,
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete(); // Revoke all tokens for the user

        return response()->json(['message' => 'Successfully logged out']);
    }
}