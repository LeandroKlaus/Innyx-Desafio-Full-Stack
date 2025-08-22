<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProdutoController extends Controller
{
    /**
     * Listar todos os produtos com paginação e busca.
     */
    public function index(Request $request)
    {
        $query = Produto::with('categoria');

        if ($request->has('busca')) {
            $termo = $request->input('busca');
            $query->where(function ($q) use ($termo) {
                $q->where('nome', 'like', '%' . $termo . '%')
                  ->orWhere('descricao', 'like', '%' . $termo . '%');
            });
        }

        $produtos = $query->paginate(10);

        return response()->json($produtos);
    }

    /**
     * Armazenar um novo produto no banco de dados.
     */
    public function store(Request $request)
    {
        try {
            // REGRAS DE VALIDAÇÃO CORRIGIDAS AQUI
            $validatedData = $request->validate([
                'nome' => 'required|string|max:50', // <-- Máximo de 50 caracteres
                'descricao' => 'required|string|max:200', // <-- Máximo de 200 caracteres
                'preco' => 'required|numeric|min:0',
                'data_validade' => 'required|date|after_or_equal:today',
                'imagem' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'categoria_id' => 'required|exists:categorias,id'
            ]);

            if ($request->hasFile('imagem')) {
                $path = $request->file('imagem')->store('produtos', 'public');
                $validatedData['imagem'] = basename($path);
            }

            $produto = Produto::create($validatedData);

            return response()->json($produto, 201);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    /**
     * Exibir um produto específico.
     */
    public function show(string $id)
    {
        $produto = Produto::with('categoria')->find($id);

        if (!$produto) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }

        return response()->json($produto);
    }

    /**
     * Atualizar um produto existente.
     */
    public function update(Request $request, string $id)
    {
        try {
            $produto = Produto::find($id);

            if (!$produto) {
                return response()->json(['message' => 'Produto não encontrado'], 404);
            }

            // REGRAS DE VALIDAÇÃO CORRIGIDAS AQUI
            $validatedData = $request->validate([
                'nome' => 'sometimes|required|string|max:50', // <-- Máximo de 50 caracteres
                'descricao' => 'sometimes|required|string|max:200', // <-- Máximo de 200 caracteres
                'preco' => 'sometimes|required|numeric|min:0',
                'data_validade' => 'sometimes|required|date|after_or_equal:today',
                'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'categoria_id' => 'sometimes|required|exists:categorias,id'
            ]);

            if ($request->hasFile('imagem')) {
                if ($produto->imagem) {
                    Storage::disk('public')->delete('produtos/' . $produto->imagem);
                }
                $path = $request->file('imagem')->store('produtos', 'public');
                $validatedData['imagem'] = basename($path);
            }

            $produto->update($validatedData);

            return response()->json($produto);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    /**
     * Remover um produto.
     */
    public function destroy(string $id)
    {
        $produto = Produto::find($id);

        if (!$produto) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }

        if ($produto->imagem) {
            Storage::disk('public')->delete('produtos/' . $produto->imagem);
        }

        $produto->delete();

        return response()->json(null, 204);
    }
}