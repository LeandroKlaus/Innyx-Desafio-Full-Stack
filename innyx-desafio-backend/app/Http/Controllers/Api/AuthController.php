<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Registra um novo usuário.
     */
    public function register(Request $request)
    {
        // Valida os dados de entrada
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Cria o usuário no banco de dados
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // Cria um token de acesso para o novo usuário
        $token = $user->createToken('auth_token')->plainTextToken;

        // Retorna os dados do usuário e o token
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201); // 201 Created
    }

    /**
     * Autentica um usuário e retorna um token.
     */
    public function login(Request $request)
    {
        // Valida os dados de entrada
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Tenta autenticar o usuário
        if (!Auth::attempt($request->only('email', 'password'))) {
            // Se a autenticação falhar, retorna erro
            return response()->json(['message' => 'Credenciais inválidas'], 401); // 401 Unauthorized
        }

        // Busca o usuário no banco
        $user = User::where('email', $request['email'])->firstOrFail();

        // Cria o token de acesso
        $token = $user->createToken('auth_token')->plainTextToken;

        // Retorna o token e os dados do usuário
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    /**
     * Faz o logout do usuário (revoga o token).
     */
    public function logout(Request $request)
    {
        // Revoga (apaga) o token de acesso atual do usuário autenticado
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }
}