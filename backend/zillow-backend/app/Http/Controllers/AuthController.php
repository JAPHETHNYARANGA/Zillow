<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response; // Add this import
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegistered;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    { 
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'role' => 'required|in:buyer,homeowner,sales agent,broker,admin',
                'phone' => 'nullable|string|max:20',
                'bio' => 'nullable|string',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);
    
            $profileImagePath = $request->hasFile('profile_image')
                ? $request->file('profile_image')->store('profile_images', 'public')
                : null;
    
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
    
            $token = $user->createToken('auth_token')->plainTextToken;
    
            //Mail::to($user->email)->send(new UserRegistered($user));
    
            return response()->json([
                'user' => $user->load('roles'),
                'access_token' => $token,
            ], Response::HTTP_CREATED); // 201
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error' => $e->errors(),
                'status' => 'error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY); // 422
        } catch (\Exception $e) {
            \Log::error('Registration failed', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'An error occurred while registering the user',
                'error' => $e->getMessage(),
                'status' => 'error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // 500
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            $user = User::where('email', $credentials['email'])->first();

            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                return response()->json([
                    'message' => 'Invalid credentials',
                    'status' => 'error',
                ], Response::HTTP_UNAUTHORIZED); // 401
            }

            if ($user->status !== 'active') {
                return response()->json([
                    'message' => 'Account is inactive or suspended',
                    'status' => 'error',
                ], Response::HTTP_FORBIDDEN); // 403
            }

            $user->update(['last_login_at' => now()]);
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user->load('roles'),
                'access_token' => $token,
                'status' => 'success',
            ], Response::HTTP_OK); // 200
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error' => $e->errors(),
                'status' => 'error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY); // 422
        } catch (\Exception $e) {
            \Log::error('Login failed', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return response()->json([
                'message' => 'An error occurred while logging in',
                'error' => $e->getMessage(),
                'status' => 'error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // 500
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            return response()->json([
                'message' => 'Successfully logged out',
                'status' => 'success',
            ], Response::HTTP_OK); // 200
        } catch (\Exception $e) {
            \Log::error('Logout failed', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return response()->json([
                'message' => 'An error occurred while logging out',
                'error' => $e->getMessage(),
                'status' => 'error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // 500
        }
    }
}