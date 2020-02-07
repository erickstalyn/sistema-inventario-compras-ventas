<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Venta;
use App\Detalle_venta;

class DetalleVentaController extends Controller {
    
    public function list(Request $request) {
        if ( !$request->ajax() ) return redirect('/');

        $venta_id = $request->venta_id;

        $list = Venta::findOrFail($venta_id)->getDetalleVenta;

        return $list;
    }

    public function listVer(Request $request) {
        if ( !$request->ajax() ) return redirect('/');

        $venta_id = $request->venta_id;

        $list = Venta::findOrFail($venta_id)->getDetalleVenta;

        return $list;
    }

    public function listEditar(Request $request) {
        if ( !$request->ajax() ) return redirect('/');

        $venta_id = $request->venta_id;

        $list = Detalle_venta::select('detalle_venta.id', 'detalle_venta.detalle_producto_id', 'detalle_venta.nombre_producto', 'detalle_venta.cantidad', 'detalle_venta.subtotal', 'detalle_venta.cantidad_fallido',
                                        'dp.precio_menor', 'dp.precio_mayor', 'dp.substock')
                            ->leftJoin('detalle_producto as dp', 'detalle_venta.detalle_producto_id', '=', 'dp.id')
                            ->where('detalle_venta.venta_id', '=', $venta_id)
                            ->orderBy('id', 'asc')->get();

        return $list;
    }
}
