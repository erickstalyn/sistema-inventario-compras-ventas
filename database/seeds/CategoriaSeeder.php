<?php

use Illuminate\Database\Seeder;
use App\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Categoria::create(['nombre' => 'Mochila']);
        Categoria::create(['nombre' => 'Celular']);
        Categoria::create(['nombre' => 'Campera']);
        Categoria::create(['nombre' => 'Gorro']);
        Categoria::create(['nombre' => 'Cartera']);
        Categoria::create(['nombre' => 'Maleta']);
        Categoria::create(['nombre' => 'Cartuchera']);
        Categoria::create(['nombre' => 'Condon']);
        Categoria::create(['nombre' => 'Alcohol']);
        Categoria::create(['nombre' => 'Mascarilla']);
    }
}
