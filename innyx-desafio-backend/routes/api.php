<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\AuthController;

Route::get('/create-test-user', function() {
    User::where('email', 'teste@innyx.com')->delete();
    $user = User::create([
        'name' => 'Usuario Teste',
        'email' => 'teste@innyx.com',
        'password' => Hash::make('password'),
    ]);
    return response()->json([
        'message' => 'Usuário de teste criado com sucesso!',
        'user' => $user
    ]);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('categorias', [CategoriaController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('produtos', ProdutoController::class);
});