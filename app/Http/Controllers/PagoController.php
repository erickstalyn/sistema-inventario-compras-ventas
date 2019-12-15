<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Pago;

class PagoController extends Controller
{
    public function agregar(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        $estado = 1;
        try {
            DB::beginTransaction();
            $listaPagos = $request->listaPagos;
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
            if($e != null) $estado = 0;
            echo($e);
        }
        
        // return ['estado' => $estado];
    }
}
