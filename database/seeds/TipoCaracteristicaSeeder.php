<?php

use Illuminate\Database\Seeder;
use App\TipoCaracteristica;

class TipoCaracteristicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoCaracteristica::create([
            'nombre' => 'tamaño'
        ]);
        TipoCaracteristica::create([
            'nombre' => 'color'
        ]);
    }
}
