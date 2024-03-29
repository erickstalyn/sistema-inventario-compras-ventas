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

        $materiales = Material::select('id', 'nombre','subtipo', 'unidad', 'costo')
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
                            ->orderBy('id', 'desc')->paginate($filas);

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

        try {
            DB::beginTransaction();

            $material = new Material();
            $material->nombre = $request->nombre;
            $material->subtipo = $request->subtipo;
            $material->unidad = $request->unidad;
            $material->costo = $request->costo;
            $material->save();

            DB::commit();
            return [
                'success' => true
            ];
        } catch(Exception $e) {
            DB::rollback();
            return [
                'success' => false,
                'exception' => $e
            ];
        }

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
            return [
                'success' => true
            ];
        } catch(Exception $e) {
            DB::rollback();
            return [
                'success' => false,
                'exception' => $e
            ];
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

    public function selectMaterial(Request $request){
        $materiales = Material::orderBy('nombre', 'desc')->get();
        return $materiales;
    }
    
    public function generatePdf(){
        $material = Material::select('nombre','subtipo', 'unidad', 'costo', 'estado')
                        ->orderBy('nombre', 'asc')->get();

        $cont = Material::count();
        
        $pdf = \PDF::loadView('pdf.materialpdf', ['material'=>$material, 'cont'=>$cont]);
        return $pdf->download('lista_materiales_silmar.pdf');
    }
}
