<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Centro;
use Illuminate\Support\Facades\DB;

class CentroController extends Controller {
    
    public function selectCentro(Request $request){
        if ( !$request->ajax() ) return redirect('/');
        $id = $request->id;
        $tipo = $request->tipo;
        $centros = Centro::select('id', 'nombre', 'direccion', 'telefono')
                            ->where(function ($query) use ($tipo) {
                                if ( $tipo != '' ) {
                                    $query->where('tipo','=', $tipo);
                                }
                            })
                            ->where(function ($query) use ($id) {
                                if ( $id != '' ) {
                                    $query->where('id','=', $id);
                                }
                            })
                            ->orderBy('id', 'asc')->get();
        return $centros;
    }

    public function selectCentroEnvio(Request $request){
        if(!$request->ajax()) return redirect('/');
        $idCentro = $request->idCentro;

        $centros = Centro::select('id', 'nombre')
                            ->where('id', '<>', $idCentro)
                            ->orderby('id', 'asc')->get();
        return $centros;
    }

    public function listProductos(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        $texto = $request->texto;

        $list = Centro::findOrFail($request->centro_id)->getProductos()->where(function ($query) use ($texto) {
                                                        if ( $texto != '' ) {
                                                            $query->where('nombre', 'like', $texto.'%')
                                                                ->orWhere('codigo', 'like', '%'.$texto.'%');
                                                        }
                                                    })->orderBy('substock', 'asc')->paginate($request->rows);

        return [
            'paginate' => [
                'currentPage' => $list->currentPage(),
                'total' => $list->total(),
                'perPage' => $list->perPage(),
                'lastPage' => $list->lastPage(),
                'firstItem' => $list->firstItem(),
                'lastItem' => $list->lastItem()
            ],
            'list' => $list
        ];
    }
    
    public function editar(Request $request){
        if ( !$request->ajax() ) return redirect('/');
        $estado = 1;
        try {
            DB::beginTransaction();

            $centro = Centro::findOrFail($request->id);
            $centro->nombre = $request->nombre;
            $centro->direccion = $request->direccion;
            $centro->save();

            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            if($e != null) $estado = 0;
        }
        return ['estado' => $estado];
    }
}
