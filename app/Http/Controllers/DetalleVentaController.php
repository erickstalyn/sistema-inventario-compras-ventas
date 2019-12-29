<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Venta;
use App\Detalle_venta;

class DetalleVentaController extends Controller {
    
    public function listVer(Request $request) {
        if ( !$request->ajax() ) return redirect('/');

        $venta_id = $request->venta_id;

        $list = Venta::findOrFail($venta_id)->getDetalleVenta;

        return $list;
    }

    public function listEditar(Request $request) {
        if ( !$request->ajax() ) return redirect('/');

        $venta_id = $request->venta_id;

        $list = Detalle_venta::select('detalle_venta.id', 'detalle_venta.detalle_producto_id', 'detalle_venta.nombre_producto', 'detalle_venta.cantidad', DB::raw('detalle_venta.cantidad as cantidad_inicial'), 'detalle_venta.subtotal', 'detalle_venta.fallidos', DB::raw('detalle_venta.fallidos as fallidos_inicial'),
                                        'detalle_producto.precio_menor', 'detalle_producto.precio_mayor', 'detalle_producto.substock', DB::raw('1 as estado'))
                            ->leftJoin('detalle_producto', 'detalle_venta.detalle_producto_id', '=', 'detalle_producto.id')
                            ->where('detalle_venta.venta_id', '=', $venta_id)
                            ->orderBy('id', 'asc')->get();

        return $list;
    }
}
