<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'descricao',
        'preco',
        'data_validade',
        'imagem',
        'categoria_id',
    ];

    /**
     * Define a relação: um Produto pertence a uma Categoria.
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}