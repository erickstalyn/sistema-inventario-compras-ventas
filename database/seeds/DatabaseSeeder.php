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
        $this->call(TipoUnidadSeeder::class);
        $this->call(UnidadSeeder::class);
        $this->call(ConversionSeeder::class);
        $this->call(TipoCaracteristicaSeeder::class);
        $this->call(CaracteristicaSeeder::class);
    
        $this->call(PersonaSeeder::class);
        $this->call(FuncionSeeder::class);
        $this->call(Detalle_funcionSeeder::class);

        $this->call(EmpresaSeeder::class);
        $this->call(CentroSeeder::class);
        
        $this->call(RolSeeder::class);
        $this->call(UsuarioSeeder::class);
    }
}
