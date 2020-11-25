<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Envio;
use App\Abasto;
// use DateTime;

class EnvioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $abastos = Abasto::all();
        $faker = Faker::create();

        foreach ($abastos as $abasto) {
            $updated_at = $faker->dateTimeBetween($abasto->created_at . " +5 days", $abasto->created_at . " +50 days");
            if ( $updated_at > (new DateTime()) ) $updated_at = NULL;

            factory(Envio::class, 1)->create([
                'abasto_id' => $abasto->id,
                'created_at' => $abasto->created_at,
                'updated_at' => $updated_at,
                'estado' => 0
            ]);
        }

        $envios = Envio::where('updated_at', '<>', NULL)->get();

        foreach ($envios as $envio) {
            $envio->estado = 1;
            $envio->updated_at = $envio->updated_at;
            $envio->save();
        };
    }
}
