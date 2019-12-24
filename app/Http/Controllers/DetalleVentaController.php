<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Venta;

class DetalleVentaController extends Controller {
    
    public function list(Request $request) {
        if ( !$request->ajax() ) return redirect('/');

        $venta_id = $request->venta_id;

        $list = Venta::findOrFail($venta_id)->getDetalleVenta;

        return $list;
    }
}
