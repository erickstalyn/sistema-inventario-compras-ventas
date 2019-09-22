<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Usuario;
use App\Persona;
use App\Rol;

class UsuarioController extends Controller
{

    public function index(Request $request){
        if ( !$request->ajax() ) return redirect('/') ;
        
        $estado = $request->estado;
        $texto = $request->texto;
        $items_per_page = 2;

        $usuarios = Usuario::join('persona', 'usuario.persona_id', '=', 'persona.id')->join('rol', 'usuario.rol_id', '=', 'rol.id')
                            ->select('persona.nombre', 'persona.direccion', 'persona.created_at as fecha_creacion', 'persona.updated_at as fecha_actualizacion', 'persona.deleted_at as fecha_eliminacion',
                                    'usuario.id', 'usuario.estado', 'rol.nombre as rol', 'rol.id as rol_id')
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
                            ->orderBy('rol', 'asc')->paginate($items_per_page);

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

    public function create(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        try {
            DB::beginTransaction();

            $persona = new Persona();
            $persona->nombre = $request->nombre;
            // $persona->dni = $request->dni==''?NULL:$request->dni;
            // $persona->ruc = $request->ruc==''?NULL:$request->ruc;
            // $persona->direccion = $request->direccion==''?NULL:$request->direccion;
            // $persona->telefono = $request->telefono==''?NULL:$request->telefono;
            // $persona->email = $request->email==''?NULL:$request->email;
            // $persona->birthday = $request->birthday==''?NULL:$request->birthday;
            // $persona->observacion = $request->observacion==''?NULL:$request->observacion;
            $persona->dni = $request->dni;
            $persona->ruc = $request->ruc;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->birthday = $request->birthday;
            $persona->observacion = $request->observacion;
            $persona->tipo = $request->tipo;
            $persona->save();

            $usuario = new Usuario();
            $usuario->persona_id = $persona->id;
            $usuario->rol_id = $request->rol_id;
            $usuario->usuario = $request->usuario;
            $usuario->password = bcrypt($request->password);
            $usuario->estado = 1;
            $usuario->save();

            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
        }
    }

    public function selectRol(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        $roles = Rol::select('id', 'nombre')
                    ->orderBy('nombre', 'asc')->get();

        return $roles;
    }
}
