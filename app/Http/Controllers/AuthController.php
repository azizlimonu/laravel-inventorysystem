<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login( AuthLoginRequest $request )
    {
        $credentials = $request->validated();

        if( !Auth::attempt( $credentials ) )
        {
            return response([
                'message' => 'Correo o contraseña incorrecta'
            ], 422);
        }

        $user = User::find( Auth::user()['id'] );
        $token = $user->createToken('token')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {

        $user = $request->user();
        $user->currentAccessToken()->delete();

        return response([
            'message' => 'Sesion cerrada',
            'emailVerfy' => $user['email_verified_at']
        ],201);
    }

    public function register( AuthRegisterRequest $request )
    {
        $request->validated();

        $user = User::create( $request->all() );

        return $user;
    }
}
