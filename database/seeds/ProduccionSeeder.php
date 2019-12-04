<?php

use Illuminate\Database\Seeder;

class ProduccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('produccion')->insert(array(
            'almacen_id' => 1,
            'total'=>1250.50,
            'fecha_inicio' => '2019/12/04',
            'fecha_programada' => '2020/01/13',
        ));
        DB::table('produccion')->insert(array(
            'almacen_id' => 2,
            'total'=>560.90,
            'fecha_inicio' => '2019/11/30',
            'fecha_programada' => '2019/12/28',
        ));
        DB::table('produccion')->insert(array(
            'almacen_id' => 1,
            'total'=>1250.50,
            'fecha_inicio' => '2019/09/27',
            'fecha_programada' => '2019/10/28',
            'fecha_fin' => '2019/10/29'
        ));
        DB::table('produccion')->insert(array(
            'almacen_id' => 1,
            'total'=>500.60,
            'fecha_inicio' => '2019/12/01',
            'fecha_programada' => '2019/12/02',
            'fecha_fin' => '2019/12/02'
        ));
    }
}
