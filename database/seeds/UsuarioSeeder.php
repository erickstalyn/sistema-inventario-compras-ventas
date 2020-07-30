<?php

use Illuminate\Database\Seeder;
use App\Usuario;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon\Carbon::now('America/Lima')->toDateTimeString();

        $persona_id = App\Persona::select('id')->where('nombres', '=', 'Jose Guzman')->first()['id'];
        $rol_id = App\Rol::select('id')->where('descripcion', '=', 'ADMINISTRADOR')->first()['id'];

        Usuario::create(array(
            'persona_id' => $persona_id,
            'usuario' => 'silmar',
            'password' => bcrypt('silmar'),
            'rol_id' => $rol_id,
            'created_at' => $now
        ));

        $rol_id = App\Rol::select('id')->where('descripcion', '=', 'PUESTO')->first()['id'];
        $centros = App\Centro::select('id', 'numero_serie')->where('tipo', '=', 'P')->get();
        foreach ($centros as $centro) {
            Usuario::create(array(
                'persona_id' => $persona_id,
                'centro_id' => $centro['id'],
                'usuario' => 'puesto'.$centro['numero_serie'],
                'password' => bcrypt('puesto'),
                'rol_id' => $rol_id,
                'created_at' => $now
            ));
        }

        $rol_id = App\Rol::select('id')->where('descripcion', '=', 'ALMACÉN')->first()['id'];
        $centros = App\Centro::select('id', 'numero_serie')->where('tipo', '=', 'A')->get();
        foreach ($centros as $centro) {
            Usuario::create(array(
                'persona_id' => $persona_id,
                'centro_id' => $centro['id'],
                'usuario' => 'almacen'.$centro['numero_serie'],
                'password' => bcrypt('almacen'),
                'rol_id' => $rol_id,
                'created_at' => $now
            ));
        }
    }
}
