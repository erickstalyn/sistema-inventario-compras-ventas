<?php

use Illuminate\Database\Seeder;
use App\Marca;

class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Marca::create(['nombre' => 'Toyota']);
        Marca::create(['nombre' => 'Hundai']);
        Marca::create(['nombre' => 'Silmar']);
        Marca::create(['nombre' => 'Studio']);
        Marca::create(['nombre' => 'Brazzers']);
        Marca::create(['nombre' => 'Pornhub']);
        Marca::create(['nombre' => 'El bisonte']);
        Marca::create(['nombre' => 'Standford']);
        Marca::create(['nombre' => 'Huawei']);
        Marca::create(['nombre' => 'Movistar']);
    }
}
