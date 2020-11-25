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
        
        $this->call(FuncionSeeder::class);
        $this->call(Detalle_funcionSeeder::class);
        $this->call(RolSeeder::class);
        $this->call(UsuarioSeeder::class);
        
        $this->call(CategoriaSeeder::class);
        $this->call(ProductoSeeder::class);
        $this->call(DetalleProductoSeeder::class);
        
        $this->call(AbastoSeeder::class);
        $this->call(DetalleAbastoSeeder::class);
        $this->call(EnvioSeeder::class);

        $this->call(DetalleMovimientoSeeder::class);
    }
}
