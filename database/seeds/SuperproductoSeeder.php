<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class SuperproductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now('America/Lima')->toDateString();
        DB::table('superproducto')->insert(array(
            'nombre' => 'Campera SILMAR R3D',
            'descripcion' => 'Ideal para viajeros extranjeros',
            'superstock' => 0,
            'created_at' => $now
        ));
    }
}
