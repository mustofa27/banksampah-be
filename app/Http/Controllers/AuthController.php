<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
class AuthController extends Controller
{

    public function login(Request $request)
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return new UserResource(true, 'User profile retrieved successfully',Auth::user());
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return new UserResource(true, 'Logout successfully',null);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return new UserResource(true, 'Token retrieved successfully', [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
