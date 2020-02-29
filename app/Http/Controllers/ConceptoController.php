<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Concepto;
use Carbon\Carbon;

class ConceptoController extends Controller {
    
    public function addIngreso (Request $request) {
        if ( !$request->ajax() ) return redirect('/');

        $now = Carbon::now('America/Lima')->toDateTimeString();

        $exception = NULL;

        try {
            DB::beginTransaction();
            $state = 'transaction-start';
            
            $concepto = new Concepto();
            $concepto->caja_id = $request->caja_id;
            $concepto->type = 1;
            $concepto->descripcion = $request->descripcion;
            $concepto->monto = $request->monto;
            $concepto->created_at = $now;
            $concepto->save();

            DB::commit();
            $state = 'transaction-success';
        } catch (Exception $e) {
            DB::rollback();
            $state = 'transaction-exception';
            $exception = $e;
        }

        return [
            'state' => $state,
            'exception' => $exception,
        ];
    }

    public function addEgreso (Request $request) {
        if ( !$request->ajax() ) return redirect('/');

        $now = Carbon::now('America/Lima')->toDateTimeString();

        $exception = NULL;

        try {
            DB::beginTransaction();
            $state = 'transaction-start';
            
            $concepto = new Concepto();
            $concepto->caja_id = $request->caja_id;
            $concepto->type = 0;
            $concepto->descripcion = $request->descripcion;
            $concepto->monto = $request->monto;
            $concepto->created_at = $now;
            $concepto->save();

            DB::commit();
            $state = 'transaction-success';
        } catch (Exception $e) {
            DB::rollback();
            $state = 'transaction-exception';
            $exception = $e;
        }

        return [
            'state' => $state,
            'exception' => $exception,
        ];
    }
}
