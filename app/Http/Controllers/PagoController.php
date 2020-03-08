<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Pago;
use App\Venta;
use App\Concepto;
use Carbon\Carbon;
use App\Http\Controllers\CajaController;

class PagoController extends Controller {

    public function agregar(Request $request){
        $state = 'transaction-validate';
        $step = NULL;
        $message = NULL;

        if ( !$request->ajax() ) return redirect('/');

        $centro = $request->dataCentro;
        if ( $centro['id'] != NULL ) {
            $box = CajaController::validate_box_open($centro['id']);
            if ( $box == NULL ) {
                return [
                    'state' => $state
                ];
            }
        }

        $listaPagos = $request->listaPagos;
        $now = Carbon::now('America/Lima')->toDateTimeString();

        try {
            DB::beginTransaction();
            $state = 'transaction-begin';
            
            foreach($listaPagos as $ep => $pag){
                $pago = new Pago();
                $pago->monto = $pag['monto'];
                $request->idVenta == '' ? $pago->abasto_id = $request->idAbasto : $pago->venta_id = $request->idVenta;
                $pago->created_at = $pag['created_at'];
                $pago->save();

                if ( $pago->venta_id != NULL ) {
                    $concepto = new Concepto();
                    $concepto->caja_id = $box->id;
                    $concepto->type = 1;
                    $concepto->descripcion = 'Pago de venta al credito';
                    $concepto->monto = $pago->monto;
                    $concepto->created_at = $now;
                    $concepto->save();
                }
            }

            DB::commit();
            $state = 'transaction-success';
        } catch(Exception $e) {
            DB::rollback();
            $state = 'transaction-exception';
            $message = $e;
            // if($e != null) $estado = 0;
            // echo($e);
        }

        return [
            'state' => $state,
            'step' => $step,
            'message' => $message
        ];
    }

    public function listVenta(Request $request) {
        if ( !$request->ajax() ) return redirect('/');

        $list = Venta::findOrFail($request->venta_id)->getPago;
        
        return $list;
    }
    
}
