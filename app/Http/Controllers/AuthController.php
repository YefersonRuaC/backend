<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    //Usando custom request, con mensajes personalizado segun el caso
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();//Validamos el request

        //Creamos el usuario
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            // 'token' => $user->createToken('token')->plainTextToken,
            'user' => $user
        ], Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        //Validacion del intento de password que ingresa el usuario
        if(!Auth::attempt($data)) {
            return response()->json([
                'error' => 'Credenciales no validas'
            ], Response::HTTP_UNAUTHORIZED);
        }

        //Tomamos el usuario autenticado y el asignamos un token, con el metodo createToken() de Sanctum
        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;

        //Respuesta con el token y el usuario que se autentico
        return response()->json([
            'token' => $token,
            'user' => $user
        ], Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();//Eliminamos el token que habilitaba la sesion del usuario

        return response()->json([
            'user' => null
        ], Response::HTTP_OK);
    }
}
