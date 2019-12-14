<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Detalle_producto;

class Detalle_productoController extends Controller
{
    public function getDetalle_productoFiltrado(Request $request){//Metodo para filtrar los productos por centro :D
        if ( !$request->ajax() ) return redirect('/');
        $idCentro = $request->idCentro;
        $texto = $request->texto;

        $productos = Detalle_producto::select('producto.id', 'producto.codigo as codigo', 'producto.nombre as nombre', 'detalle_producto.substock as substock')
                            ->join('producto', 'producto.id', 'detalle_producto.producto_id')
                            ->where(function ($query) use ($texto) {
                                if ( $texto != '' ) {
                                    $query->where('producto.nombre', 'like', $texto . '%')
                                        ->orWhere('producto.codigo', '=', $texto);
                                }
                            })
                            ->where('detalle_producto.centro_id', '=', $idCentro)
                            ->orderBy('producto.nombre', 'asc')->get();
        return [
            'productos' => $productos
        ];
    }
}
