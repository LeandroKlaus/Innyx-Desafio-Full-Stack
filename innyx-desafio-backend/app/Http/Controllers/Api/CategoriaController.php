<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Busca todas as categorias no banco de dados
        $categorias = Categoria::all();

        // Retorna as categorias em formato JSON
        return response()->json($categorias);
    }
}