<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Producto;
use App\Categoria;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoria_id = Categoria::where('nombre', 'like', '%herramientas%')->first()['id'];
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Destornillador electrico']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Atornillador electrico']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Pinza amperimetrica digital']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Set de herramientas Bosch']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Caja de herramientas Stanley']);

        $categoria_id = Categoria::where('nombre', 'like', '%construccion%')->first()['id'];
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Nivel torpedo de alto impacto']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Cinta teflon']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Pistola para silicona']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Mascara para soldar']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Martillo']);

        $categoria_id = Categoria::where('nombre', 'like', '%escaleras%')->first()['id'];
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Escalera redline tijera']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Escalon plegable mica']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Escalera aluminio 5 peldaños']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Escalera articulada']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Escalera taburete']);

        $categoria_id = Categoria::where('nombre', 'like', '%cerrajeria%')->first()['id'];
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Candado de hierro']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Suministros de cerrajero']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Candado alarma antirrobo']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Cerradura electronica']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Cargador de pilas']);

        $categoria_id = Categoria::where('nombre', 'like', '%maquinarias%')->first()['id'];
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Nano spray atomizador']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Selladora termica']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Soplador de aire']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Aspiradora']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Detector de metales']);

        $categoria_id = Categoria::where('nombre', 'like', '%instalaciones%')->first()['id'];
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Timbre inalambrico']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Kit cautín mas lupa']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Supresor de pico']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Extension electrica']);

        $categoria_id = Categoria::where('nombre', 'like', '%jardineria%')->first()['id'];
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Porta mangera de pared']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Rociador dispersador']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Fumigador regulable']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Pulverizador']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Mangera expandible']);

        $categoria_id = Categoria::where('nombre', 'like', '%equipos%')->first()['id'];
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Mascarilla KN95']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Orejeras']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Protector facial']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Lentes de seguridad']);
        factory(Producto::class, 1)->create(['categoria_id' => $categoria_id, 'nombre' => 'Botas gruesas']);
    }
}
