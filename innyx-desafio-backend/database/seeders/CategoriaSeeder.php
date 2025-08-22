<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Importe a classe DB

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insere algumas categorias de exemplo
        DB::table('categorias')->insert([
            ['nome' => 'Laticínios'],
            ['nome' => 'Bebidas'],
            ['nome' => 'Higiene Pessoal'],
            ['nome' => 'Limpeza'],
            ['nome' => 'Mercearia'],
        ]);
    }
}
