<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
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
        DB::table('categoria')->insert(['nombre' => 'Juegos de herramientas']);
        DB::table('categoria')->insert(['nombre' => 'Construcción']);
        DB::table('categoria')->insert(['nombre' => 'Escaleras']);
        DB::table('categoria')->insert(['nombre' => 'Cerrajería']);
        DB::table('categoria')->insert(['nombre' => 'Maquinarias']);
        DB::table('categoria')->insert(['nombre' => 'Instalaciones eléctricas']);
        DB::table('categoria')->insert(['nombre' => 'Jardinería']);
        DB::table('categoria')->insert(['nombre' => 'Equipos de seguridad']);
    }
}
