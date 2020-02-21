<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Persona;

class PersonaController extends Controller
{
    public function listar(Request $request){
        if ( !$request->ajax() ) return redirect('/');
        $funcion = $request->funcion;
        $estado = $request->estado;
        $texto = $request->texto;
        $filas = $request->filas;

        $personas = Persona::select('id', 'nombres', 'apellidos','razon_social', 'dni', 'ruc', 'direccion', 'telefono', 'email', 'tipo', 'estado')
                        ->join('detalle_funcion','detalle_funcion.persona_id', 'persona.id')
                        ->where(function ($query) use ($estado) {
                            if ( $estado != 2 ) {
                                $query->where('detalle_funcion.estado', '=', $estado);
                            }
                        })
                        ->where(function ($query) use ($texto) {
                            if ( $texto != '' ) {
                                $query->where('persona.nombres', 'like', '%'.$texto.'%')
                                    ->orWhere('persona.apellidos', 'like', '%'.$texto.'%')
                                    ->orWhere('persona.razon_social', 'like', $texto.'%')
                                    ->orWhere('persona.ruc', $texto);
                            }
                        })
                        ->where(function ($query) use ($funcion) {
                            if ( $funcion != '' ) {
                                $query->where('detalle_funcion.funcion_id', $funcion);
                            }
                        })
                        ->orderBy('persona.id', 'desc')->paginate($filas);

        return [
            'paginacion' => [
                'total' => $personas->total(),
                'currentPage' => $personas->currentPage(),
                'perPage' => $personas->perPage(),
                'lastPage' => $personas->lastPage(),
                'firstItem' => $personas->firstItem(),
                'lastItem' => $personas->lastItem()
            ],
            'personas' => $personas
        ];
    }
    public function getPersona(Request $request){
        if ( !$request->ajax() ) return redirect('/');
        $documento = $request->documento;

        $persona = Persona::select('id', 'nombres', 'apellidos', 'razon_social')
                            ->where('dni', '=', $documento)
                            ->orWhere('ruc', '=', $documento)
                            ->get();
        return [
            'persona' => $persona
        ];
    }

}
