<?php

use Illuminate\Database\Seeder;
use App\Envio;

class EnvioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Envio::create(array(//Envio de un abasto realizado por un ADMIN y enviado a un almacen(centro)
            'centro_to_id' => 4,
            'abasto_id' => 1,
            'tipo' => 1,
        ));
    }
}
