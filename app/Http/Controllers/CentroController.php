<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Centro;

class CentroController extends Controller {
    
    public function selectAlmacen(Request $request){
        if ( !$request->ajax() ) return redirect('/');
        $almacenes = Centro::select('id', 'nombre')
                            ->where('tipo','=','A')
                            ->orderBy('nombre', 'asc')->get();
        return $almacenes;
    }

    public function selectCentro(Request $request){
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
                                                    })->orderBy('id', 'desc')->paginate($request->rows);

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
}
