<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Material;

class MaterialController extends Controller
{
    public function listar(Request $request){
        if ( !$request->ajax() ) return redirect('/') ;
        
        $estado = $request->estado;
        $texto = $request->texto;
        $filas = $request->filas;
        $ordenarPor = $request->ordenarPor;
        $orden = $request->orden;

        $materiales = Material::select('id', 'nombre', 'unidad', 'costo', 'estado')
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

    public function selectTipoUnidad(Request $request){
        $categorias = Categoria::select('id', 'nombre')
                            ->where('estado', '=', 1)
                            ->orderBy('nombre', 'desc')->get();

        return $categorias;
    }
}
