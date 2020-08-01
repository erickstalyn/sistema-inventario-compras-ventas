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
            'nombre' => 'Tamaño'
        ]);
        TipoCaracteristica::create([
            'nombre' => 'Color'
        ]);
    }
}
