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
        DB::table('centro')->insert(array(
            'nombre' => 'Mi Puesto A',
            'direccion' => 'Chiclayo - Av. Tomas valle #754',
            'telefono' => '74383898',
            'tipo' => 'P',
            'created_at' => $now
        ));
        // DB::table('centro')->insert(array(
        //     'nombre' => 'Mi Puesto B',
        //     'direccion' => 'Lambayeque - Call. Alfonso Ugarte #232',
        //     'telefono' => '74343575',
        //     'tipo' => 'P',
        //     'created_at' => $now
        // ));
        // DB::table('centro')->insert(array(
        //     'nombre' => 'Mi Puesto C',
        //     'direccion' => 'Iquitos - Call. Jose Balta #156',
        //     'telefono' => '73456743',
        //     'tipo' => 'P',
        //     'created_at' => $now
        // ));
        DB::table('centro')->insert(array(
            'nombre' => 'Mi Almacen 1',
            'direccion' => 'Chiclayo - Call. San Felipe #443',
            'telefono' => '75849790',
            'tipo' => 'A',
            'created_at' => $now
        ));
        // DB::table('centro')->insert(array(
        //     'nombre' => 'Mi Almacen 2',
        //     'direccion' => 'Lambayeque - Call. Tacna libertadora #455',
        //     'telefono' => '74937598',
        //     'tipo' => 'A',
        //     'created_at' => $now
        // ));
        // DB::table('centro')->insert(array(
        //     'nombre' => 'Mi Almacen 3',
        //     'direccion' => 'Iquitos - Call. Julio Valdiviezo #273',
        //     'telefono' => '74937598',
        //     'tipo' => 'A',
        //     'created_at' => $now
        // ));
    }
}
