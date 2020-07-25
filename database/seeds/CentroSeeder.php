<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CentroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now('America/Lima')->toDateString();
        DB::table('centro')->insert(array(//1
            'empresa_id' => 1,
            'numero_serie' => '0001',
            'nombre' => 'Mi Puesto A',
            'direccion' => 'CAMPO FERIAL AMAZONAS JR. AMAZONAS 299 Int 10',
            'telefonos' => [],
            'numeros_correlativos' => [],
            'created_at' => $now
        ));
        DB::table('centro')->insert(array(//2
            'nombre' => 'Mi Puesto B',
            'direccion' => 'Lambayeque - Call. Alfonso Ugarte #232',
            'telefono' => '74343575',
            'tipo' => 'P',
            'created_at' => $now
        ));
        DB::table('centro')->insert(array(//3
            'nombre' => 'Mi Puesto C',
            'direccion' => 'Iquitos - Call. Jose Balta #156',
            'telefono' => '73456743',
            'tipo' => 'P',
            'created_at' => $now
        ));
        DB::table('centro')->insert(array(//4
            'nombre' => 'Mi Almacen 1',
            'direccion' => 'Chiclayo - Call. San Felipe #443',
            'telefono' => '75849790',
            'tipo' => 'A',
            'created_at' => $now
        ));
        DB::table('centro')->insert(array(//5
            'nombre' => 'Mi Almacen 2',
            'direccion' => 'Lambayeque - Call. Tacna libertadora #455',
            'telefono' => '74937598',
            'tipo' => 'A',
            'created_at' => $now
        ));
        DB::table('centro')->insert(array(//6
            'nombre' => 'Mi Almacen 3',
            'direccion' => 'Iquitos - Call. Julio Valdiviezo #273',
            'telefono' => '74937598',
            'tipo' => 'A',
            'created_at' => $now
        ));
    }
}
