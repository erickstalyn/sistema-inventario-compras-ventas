<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Material;
use App\Data;
use Exception;

class MaterialController extends Controller
{
    public function listar(Request $request){
        if ( !$request->ajax() ) return redirect('/') ;
        
        $estado = $request->estado;
        $texto = $request->texto;
        $filas = $request->filas;
        $ordenarPor = $request->ordenarPor;
        $orden = $request->orden;

        $materiales = Material::select('id', 'nombre','subtipo', 'unidad', 'costo', 'estado')
                            ->where(function ($query) use ($estado) {
                                if ( $estado != 2 ) {
                                    $query->where('estado', '=', $estado);
                                }
                            })
                            ->where(function ($query) use ($texto) {
                                if ( $texto != '' ) {
                                    $query->where('nombre', 'like', '%'.$texto.'%');
                                }
                            })
                            ->orderBy($ordenarPor, $orden)->paginate($filas);

        return [
            'paginacion' => [
                'total' => $materiales->total(),
                'currentPage' => $materiales->currentPage(),
                'perPage' => $materiales->perPage(),
                'lastPage' => $materiales->lastPage(),
                'firstItem' => $materiales->firstItem(),
                'lastItem' => $materiales->lastItem()
            ],
            'materiales' => $materiales
        ];
    }

    public function agregar(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        $estado = 1;
        try {
            DB::beginTransaction();

            $material = new Material();
            $material->nombre = $request->nombre;
            $material->subtipo = $request->subtipo;
            $material->unidad = $request->unidad;
            $material->costo = $request->costo;
            $material->save();

            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            if($e != null) $estado = 0;
        }
        // echo($estado);
        return ['estado' => $estado];

    }
    
    public function editar(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        try {
            DB::beginTransaction();

            $material = Material::findOrFail($request->id);
            $material->nombre = $request->nombre;
            $material->subtipo = $request->subtipo;
            $material->unidad = $request->unidad;
            $material->costo = $request->costo;
            $material->save();

            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
        }
    }

    public function activar(Request $request){
        try {
            DB::beginTransaction();
            
            $material = Material::findOrFail($request->id);
            $material->estado = 1;
            $material->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }
    }

    public function desactivar(Request $request){
        try {
            DB::beginTransaction();

            $material = Material::findOrFail($request->id);
            $material->estado = 0;
            $material->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }
    }

    // public function selectUnidad(Request $request){
    //     $tipos = Data::select('id', 'tipo', 'subtipo','nombre')
    //                         ->where('tipo','=','U')
    //                         ->orderBy('id', 'asc')->get();
    //     return $tipos;
    // }
}
