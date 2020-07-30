<?php

use Illuminate\Database\Seeder;
use App\TipoUnidad;

class TipoUnidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoUnidad::create(['nombre' => 'longitud']);
        TipoUnidad::create(['nombre' => 'peso']);
        TipoUnidad::create(['nombre' => 'unidad']);
    }
}
