<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Hash;
use App\Usuario;
use App\Persona;
use App\Rol;

class UsuarioController extends Controller
{

    public function listar(Request $request){
        if ( !$request->ajax() ) return redirect('/') ;
        
        $estado = $request->estado;
        $texto = $request->texto;
        $items_per_page = $request->items;
        $ordenarPor = $request->ordenarPor;

        $usuarios = Usuario::join('persona', 'usuario.persona_id', '=', 'persona.id')->join('rol', 'usuario.rol_id', '=', 'rol.id')
                            ->select('persona.nombre', 'persona.direccion',
                                DB::raw('Date_Format(persona.created_at,\'' .'%d-%M-%Y' . '\') as fecha_creacion'), // para mysql
                                // DB::raw('to_char(persona.created_at,\'' .'DD-Mon-YYYY' . '\') as fecha_creacion'), //para postgresql
                                //'persona.created_at as fecha_creacion',  
                             'persona.updated_at as fecha_actualizacion', 'persona.deleted_at as fecha_eliminacion',
                             'usuario.id', 'usuario.usuario', 'usuario.estado', 'rol.nombre as rol', 'rol.id as rol_id')
                            ->where(function ($query) use ($estado) {
                                if ( $estado != 2 ) {
                                    $query->where('usuario.estado', '=', $estado);
                                }
                            })
                            ->where(function ($query) use ($texto) {
                                if ( $texto != '' ) {
                                    $query->where('rol.nombre', 'like', '%'.$texto.'%')
                                        ->orWhere('persona.nombre', 'like', '%'.$texto.'%');
                                }
                            })
                            ->orderBy($ordenarPor, 'asc')->paginate($items_per_page);

        return [
            'paginacion' => [
                'total' => $usuarios->total(),
                'currentPage' => $usuarios->currentPage(),
                'perPage' => $usuarios->perPage(),
                'lastPage' => $usuarios->lastPage(),
                'firstItem' => $usuarios->firstItem(),
                'lastItem' => $usuarios->lastItem()
            ],
            'usuarios' => $usuarios
        ];
    }

    public function agregar(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        try {
            DB::beginTransaction();

            $persona = new Persona();
            $persona->nombre = $request->nombre;
            // $persona->dni = $request->dni==''?NULL:$request->dni;
            // $persona->ruc = $request->ruc==''?NULL:$request->ruc;
            $persona->direccion = $request->direccion==''?NULL:$request->direccion;
            // $persona->telefono = $request->telefono==''?NULL:$request->telefono;
            // $persona->email = $request->email==''?NULL:$request->email;
            // $persona->birthday = $request->birthday==''?NULL:$request->birthday;
            // $persona->observacion = $request->observacion==''?NULL:$request->observacion;
            $persona->tipo = $request->tipo;
            $persona->save();

            $usuario = new Usuario();
            $usuario->persona_id = $persona->id;
            $usuario->rol_id = $request->rol_id;
            $usuario->usuario = $request->usuario;
            $usuario->password = bcrypt($request->password);
            $usuario->save();

            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
        }
    }

    public function editar(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        try {
            DB::beginTransaction();

            $usuario = Usuario::findOrFail($request->id);
            $persona = Persona::findOrFail($usuario->persona_id);
            
            $persona->nombre = $request->nombre;
            // $persona->dni = $request->dni==''?NULL:$request->dni;
            // $persona->ruc = $request->ruc==''?NULL:$request->ruc;
            $persona->direccion = $request->direccion==''?NULL:$request->direccion;
            // $persona->telefono = $request->telefono==''?NULL:$request->telefono;
            // $persona->email = $request->email==''?NULL:$request->email;
            // $persona->birthday = $request->birthday==''?NULL:$request->birthday;
            // $persona->observacion = $request->observacion==''?NULL:$request->observacion;
            $persona->tipo = $request->tipo;
            $persona->save();
            
            $usuario->rol_id = $request->rol_id;
            if ( $request->usuario != '' ) $usuario->usuario = $request->usuario;
            if ( $request->password != '' ) $usuario->password = bcrypt($request->password);
            $usuario->save();

            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
        }
    }

    public function activar(Request $request){
        $usuario = Usuario::findOrFail($request->id);

        $usuario->estado = 1;
        $usuario->save();
    }

    public function desactivar(Request $request){
        $usuario = Usuario::findOrFail($request->id);

        $usuario->estado = 0;
        $usuario->save();
    }

    public function comprobar(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        if ( Hash::check($request->password, Auth::user()->password) ) {
            return 1;
        } else {
            return 0;
        }
    }

    public function selectRol(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        $roles = Rol::select('id', 'nombre')
                    ->orderBy('nombre', 'asc')->get();

        return $roles;
    }
}
