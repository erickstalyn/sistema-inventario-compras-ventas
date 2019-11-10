<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PersonaSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now('America/Lima')->toDateString();

        DB::table('persona')->insert(array(
            'nombres'=>'Jose Guzman',
            'apellidos'=> 'Silva Tello',
            'created_at' => $now,
            'tipo' => 'P'
        ));
    }
}
