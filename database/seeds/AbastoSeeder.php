<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Abasto;

class AbastoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $now = Carbon::now('America/Lima')->toDateString();
        // DB::table('abasto')->insert(array(//1
        //     'total' => '1200',
        //     'tipo' => 0, 
        //     'proveedor_id' => 2,
        //     // 'proveedor_nombre' => 'Jose Anderson Cespedes Diaz',
        //     // 'administrador_id' => 1, //Persona que realizó el abasto
        //     'created_at' => $now
        // ));
        // DB::table('abasto')->insert(array(//2
        //     'total' => '590',
        //     'tipo' => 1,
        //     'total_faltante' => 0,
        //     'proveedor_id' => 3,
        //     // 'proveedor_nombre' => 'Amelia Sofía Rojas Cordova',
        //     // 'administrador_id' => 1, //Persona que realizó el abasto
        //     'created_at' => $now
        // ));

        factory(Abasto::class, 100)->create();

    }
}
