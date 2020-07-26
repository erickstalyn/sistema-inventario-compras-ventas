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
        // $this->call(PersonaSeeder::class);
        $this->call(EmpresaSeeder::class);
        $this->call(CentroSeeder::class);
        // $this->call(FuncionSeeder::class);
        // $this->call(Detalle_funcionSeeder::class);
        // $this->call(RolSeeder::class);
        // $this->call(UsuarioSeeder::class);
        // $this->call(SuperproductoSeeder::class);
        // $this->call(ProductoSeeder::class);
        // $this->call(MaterialSeeder::class);
        // $this->call(DataSeeder::class);
        // $this->call(ProduccionSeeder::class);
        // $this->call(EnvioSeeder::class);
    }
}
