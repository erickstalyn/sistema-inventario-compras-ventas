<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Pago;
use App\Venta;

class PagoController extends Controller
{
    public function agregar(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        $listaPagos = $request->listaPagos;

        try {
            DB::beginTransaction();
            
            foreach($listaPagos as $ep => $pag){
                $pago = new Pago();
                $pago->monto = $pag['monto'];
                $request->idVenta == '' ? $pago->abasto_id = $request->idAbasto : $pago->venta_id = $request->idVenta;
                $pago->created_at = $pag['created_at'];
                $pago->save();
            }

            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            // if($e != null) $estado = 0;
            // echo($e);
        }
        // return ['estado' => $estado];
    }

    public function listVenta(Request $request) {
        if ( !$request->ajax() ) return redirect('/');

        $list = Venta::findOrFail($request->venta_id)->getPago;
        
        return $list;
    }
}
