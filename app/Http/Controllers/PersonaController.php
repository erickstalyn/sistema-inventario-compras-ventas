<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Persona;
use App\Detalle_funcion;
use Illuminate\Support\Facades\DB;
use Exception;

class PersonaController extends Controller
{
    public function listar(Request $request){
        if ( !$request->ajax() ) return redirect('/');
        $funcion = $request->funcion;
        $estado = $request->estado;
        $tipo = $request->tipo;
        $texto = $request->texto;
        $filas = $request->filas;

        $personas = Persona::select('id', 'nombres', 'apellidos','razon_social', 'dni', 'ruc', 'direccion', 'telefono', 'email', 'tipo', 'estado')
                        ->join('detalle_funcion','detalle_funcion.persona_id', 'persona.id')
                        ->where(function ($query) use ($estado) {
                            if ( $estado != 2 ) {
                                $query->where('detalle_funcion.estado', '=', $estado);
                            }
                        })
                        ->where(function ($query) use ($tipo) {
                            if ( $tipo != '' ) {
                                $query->where('persona.tipo', '=', $tipo);
                            }
                        })
                        ->where(function ($query) use ($texto) {
                            if ( $texto != '' ) {
                                $query->where('persona.nombres', 'like', '%'.$texto.'%')
                                    ->orWhere('persona.apellidos', 'like', '%'.$texto.'%')
                                    ->orWhere('persona.dni', 'like', $texto . '%')
                                    ->orWhere('persona.razon_social', 'like', $texto.'%')
                                    ->orWhere('persona.ruc', 'like', $texto . '%');
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

    public function agregar(Request $request){
        if(!$request->ajax()) return redirect('/');
        $estado = 1;
        try {
            DB::beginTransaction();

            $persona = new Persona();
            $persona->tipo = $request->tipo;
            if($persona->tipo == 'P'){
                $persona->nombres = mb_convert_case($request->nombres, MB_CASE_TITLE, "UTF-8");
                $persona->apellidos = mb_convert_case($request->apellidos, MB_CASE_TITLE, "UTF-8");
                $persona->dni = $request->dni;
                
            }
            if($persona->tipo == 'E'){
                $persona->razon_social = $request->razon_social;
                $persona->ruc = $request->ruc;
            }
            $persona->direccion = mb_convert_case($request->direccion, MB_CASE_TITLE, "UTF-8");
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->save();
            //Asigno la funcion de cliente a esta Persona
            $detalle_funcion = new Detalle_funcion();
            $detalle_funcion->persona_id = $persona->id;
            $detalle_funcion->funcion_id = 1;// 1: CLIENTE, 2: PROVEEDOR, 3: TRABAJADOR
            $detalle_funcion->save();

            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            if($e != null) $estado = 0;
        }
        return ['estado' => $estado];
    }
    public function editar(Request $request){
        if(!$request->ajax()) return redirect('/');
        $estado = 1;
        try {
            DB::beginTransaction();

            $persona = Persona::findOrFail($request->id);
            if($persona->tipo == 'E') $persona->razon_social = $request->razon_social;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->save();

            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            if($e != null) $estado = 0;
        }
        return ['estado' => $estado];
    }

    public function setEstado(Request $request){
        if(!$request->ajax()) return redirect('/');
        $estado = 1;
        try {
            DB::beginTransaction();
            DB::update('update detalle_funcion set estado ='. $request->estado . ' where persona_id = ? and funcion_id = ?', [$request->id, $request->funcion]);
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            if($e != null) $estado = 0;
        }
        return ['estado' => $estado];
        
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
