<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\AuthController; // <-- IMPORTAR O NOVO CONTROLLER

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- Rotas Públicas (não precisam de autenticação) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('categorias', [CategoriaController::class, 'index']);


// --- Rotas Protegidas (exigem autenticação via Sanctum) ---
Route::middleware('auth:sanctum')->group(function () {
    // Rota para verificar usuário autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rota de logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rotas do CRUD de Produtos
    Route::apiResource('produtos', ProdutoController::class);
});