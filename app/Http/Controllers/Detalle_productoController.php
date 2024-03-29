<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Detalle_producto;
use App\Centro;

class Detalle_productoController extends Controller
{
    public function getDetalle_productoFiltrado(Request $request){//Metodo para filtrar los productos por centro :D
        if ( !$request->ajax() ) return redirect('/');
        $idCentro = $request->idCentro;
        $texto = $request->texto;

        $productos = Detalle_producto::select('producto.id', 'producto.codigo as codigo', 'producto.nombre as nombre', 'detalle_producto.substock as substock', 'detalle_producto.fallidos as fallidos')
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

    public function editar(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        try {
            DB::beginTransaction();

            $detalle_producto = Detalle_producto::findOrFail($request->id);
            $detalle_producto->precio_menor = $request->precio_menor;
            $detalle_producto->precio_mayor = $request->precio_mayor;
            $detalle_producto->save();

            DB::commit();
            $error = NULL;
        } catch(Exception $e) {
            DB::rollback();
            $error = $e;
        }

        return [
            'estado' => $error==NULL?1:0,
            'error' => $error
        ];
    }

    public function reparar(Request $request){
        if(!$request->ajax()) return redirect('/');
        try {
            DB::beginTransaction();
            $detalle_producto = Detalle_producto::findOrFail($request->detalle_producto_id);
            $detalle_producto->fallidos = $detalle_producto->fallidos - $request->cant_reparar;
            $detalle_producto->substock = $detalle_producto->substock + $request->cant_reparar;
            $detalle_producto->save();
            DB::commit();
            $error = NULL;
        } catch (Exception $e) {
            DB::rollback();
            $error = $e;
        }
        return [
            'estado' => $error==NULL?1:0,
            'error' => $error
        ];
    }

    public function listProductos(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        $text = $request->text;
        $centro_id = $request->centro_id;
        // $text = '';
        // $centro_id = 1;

        $list = Centro::findOrFail($centro_id)->getProductos()
                                            ->where(function ($query) use ($text) {
                                                if ( $text != '' ) {
                                                    $query->where('nombre', 'like', $text.'%')
                                                        ->orWhere('codigo', 'like', '%'.$text.'%');
                                                }
                                            })
                                            ->orderBy('pivot_id', 'desc')->get();

        return $list;
    }
}
