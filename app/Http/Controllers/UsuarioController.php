<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Usuario;

class UsuarioController extends Controller
{
    public function index(Request $request){
        if ( !$request->ajax() ) return redirect('/') ;
        
        $estado = $request->estado;
        $texto = $request->texto;
        $items_per_page = 4;

        $usuarios = Usuario::join('persona', 'usuario.persona_id', '=', 'persona.id')->join('rol', 'usuario.rol_id', '=', 'rol.id')
                            ->select('persona.nombre', 'persona.direccion', 'persona.created_at as fecha_creacion', 'persona.updated_at as fecha_actualizacion', 'persona.deleted_at as fecha_eliminacion',
                                    'usuario.id', 'usuario.estado', 'rol.nombre as rol')
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
}
