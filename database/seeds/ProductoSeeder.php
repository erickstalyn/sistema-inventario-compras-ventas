<?php

use Illuminate\Database\Seeder;
use App\Producto;
use App\Categoria;
use App\Marca;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon\Carbon::now('America/Lima')->toDateTimeString();

        Producto::create([
            'modelo' => $modelo = 'XXX',
            'categoria_id' => ($categoria = Categoria::where('nombre', '=', 'Condon')->first())['id'],
            'marca_id' => ($marca = Marca::where('nombre', '=', 'Brazzers')->first())['id'],
            'nombre' => $categoria['nombre'] . ' ' . $marca['nombre'] . ' ' . $modelo,
            'created_at' => $now
        ]);
        Producto::create([
            'modelo' => $modelo = 'Drakariz',
            'categoria_id' => ($categoria = Categoria::where('nombre', '=', 'Celular')->first())['id'],
            'marca_id' => ($marca = Marca::where('nombre', '=', 'Huawei')->first())['id'],
            'nombre' => $categoria['nombre'] . ' ' . $marca['nombre'] . ' ' . $modelo,
            'created_at' => $now
        ]);
        Producto::create([
            'modelo' => $modelo = 'XR',
            'categoria_id' => ($categoria = Categoria::where('nombre', '=', 'Campera')->first())['id'],
            'marca_id' => ($marca = Marca::where('nombre', '=', 'Silmar')->first())['id'],
            'nombre' => $categoria['nombre'] . ' ' . $marca['nombre'] . ' ' . $modelo,
            'created_at' => $now
        ]);
    }
}
