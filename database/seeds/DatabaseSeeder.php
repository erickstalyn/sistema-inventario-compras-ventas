<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PersonaSeeder::class);
        $this->call(CentroSeeder::class);
        $this->call(UsuarioSeeder::class);
        $this->call(SuperproductoSeeder::class);
        $this->call(ProductoSeeder::class);
        $this->call(MaterialSeeder::class);
        $this->call(DataSeeder::class);
        $this->call(ProduccionSeeder::class);
        // $this->call(AbastoSeeder::class);
        // $this->call(EnvioSeeder::class);
        $this->call(VentaSeeder::class);
    }
}
