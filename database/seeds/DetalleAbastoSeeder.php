<?php

use Illuminate\Database\Seeder;
use App\Detalle_abasto;
use App\Abasto;

class DetalleAbastoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $abastos = Abasto::all();

        foreach ($abastos as $abasto) {
            factory(Detalle_abasto::class, rand(1, 5))->create(['abasto_id' => $abasto->id]);
        }
        
    }
}
