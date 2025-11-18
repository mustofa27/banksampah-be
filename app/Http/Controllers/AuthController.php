<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\APIResource;
use Tymon\JWTAuth\Facades\JWTAuth;
class AuthController extends Controller
{

    public function login(Request $request)
    {
        $token = auth('api')->attempt($request->only('email', 'password'));
        if (! $token) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        //$token = $user->createToken('auth_token')->plainTextToken;

        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone_number' => 'required|numeric|min:9',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'profile_picture' => '',
            'phone_number'    => $request->phone_number,
        ]);

        return new APIResource(true, 'Registration success', $user);
    }

    public function me()
    {
        return new APIResource(true, 'User profile retrieved successfully',Auth::user());
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return new APIResource(true, 'Logout successfully',null);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return new APIResource(true, 'Token retrieved successfully', [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
