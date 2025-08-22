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
        // Inicia a query para buscar produtos
        $query = Produto::with('categoria'); // 'categoria' é o nome da relação que vamos criar no Model

        // Verifica se há um termo de busca para nome ou descrição
        if ($request->has('busca')) {
            $termo = $request->input('busca');
            $query->where(function ($q) use ($termo) {
                $q->where('nome', 'like', '%' . $termo . '%')
                  ->orWhere('descricao', 'like', '%' . $termo . '%');
            });
        }

        // Executa a query com paginação de 10 itens por página
        $produtos = $query->paginate(10);

        return response()->json($produtos);
    }

    /**
     * Armazenar um novo produto no banco de dados.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados recebidos
            $validatedData = $request->validate([
                'nome' => 'required|string|max:50',
                'descricao' => 'required|string|max:200',
                'preco' => 'required|numeric|min:0',
                'data_validade' => 'required|date|after_or_equal:today',
                'imagem' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'categoria_id' => 'required|exists:categorias,id'
            ]);

            // Lida com o upload da imagem
            if ($request->hasFile('imagem')) {
                // Salva a imagem na pasta 'public/produtos' e obtém o nome do arquivo
                $path = $request->file('imagem')->store('produtos', 'public');
                $validatedData['imagem'] = basename($path);
            }

            // Cria o produto no banco de dados
            $produto = Produto::create($validatedData);

            // Retorna o produto criado com status 201 (Created)
            return response()->json($produto, 201);

        } catch (ValidationException $e) {
            // Retorna os erros de validação em JSON
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    /**
     * Exibir um produto específico.
     */
    public function show(string $id)
    {
        // Busca o produto pelo ID, incluindo a categoria relacionada
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

            // Validação dos dados (imagem não é obrigatória na atualização)
            $validatedData = $request->validate([
                'nome' => 'sometimes|required|string|max:50',
                'descricao' => 'sometimes|required|string|max:200',
                'preco' => 'sometimes|required|numeric|min:0',
                'data_validade' => 'sometimes|required|date|after_or_equal:today',
                'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'categoria_id' => 'sometimes|required|exists:categorias,id'
            ]);

            // Se uma nova imagem for enviada
            if ($request->hasFile('imagem')) {
                // Deleta a imagem antiga
                if ($produto->imagem) {
                    Storage::disk('public')->delete('produtos/' . $produto->imagem);
                }
                // Salva a nova imagem
                $path = $request->file('imagem')->store('produtos', 'public');
                $validatedData['imagem'] = basename($path);
            }

            // Atualiza o produto com os novos dados
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

        // Deleta a imagem associada ao produto
        if ($produto->imagem) {
            Storage::disk('public')->delete('produtos/' . $produto->imagem);
        }

        // Deleta o produto do banco
        $produto->delete();

        // Retorna uma resposta vazia com status 204 (No Content)
        return response()->json(null, 204);
    }
}