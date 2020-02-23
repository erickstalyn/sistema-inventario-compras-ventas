<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Caja;
use Carbon\Carbon;
use Exception;

class CajaController extends Controller {
    
    public function state(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        $centro_id = $request->centro_id;
        $start = Carbon::now('America/Lima')->toDateString();

        try {
            $caja = Caja::select('state')
                    ->where('centro_id', '=', $centro_id)
                    ->where(DB::raw('CAST(start AS DATE)'), '=', $start)->first();

            if ( $caja == NULL ) {
                return [
                    'state' => 0,
                    'message' => 'No se ha creado la caja aun'
                ];
            }
            if ( $caja->state == 0 ) {
                return [
                    'state' => 0,
                    'message' => 'La caja ya esta cerrada'
                ];
            }
            
            return [
                'state' => 1
            ];
        } catch (Exception $e) {
            return [
                'state' => 0,
                'message' => 'Error al conseguir el estado de la caja'
            ];
        }
        
    }

}
