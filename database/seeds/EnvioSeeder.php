<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EnvioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now('America/Lima')->toDateString();
        DB::table('envio')->insert(array(
            'estado' => '1',
            'centro_to_id' => 4,
            'abasto_id' => 1,
            'created_at' => $now
        ));
        DB::table('envio')->insert(array(
            'estado' => '2',
            'centro_to_id' => 4,
            'abasto_id' => 2,
            'created_at' => $now,
            'updated_at' => $now
        ));
    }
}
