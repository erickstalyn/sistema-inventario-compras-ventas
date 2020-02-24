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
    
    public function get(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        // $date = $request->date;
        $date = Carbon::now('America/Lima')->toDateString();
        $centro_id = $request->centro_id;

        $caja = Caja::where(DB::raw('CAST(start AS DATE)'), '=', $date)
                    ->where('centro_id', '=', $centro_id)->first();

        if ( $caja == NULL ) {
            return [
                'exist' => false
            ];
        }
        
        $caja->getConceptos;
        return [
            'exist' => true,
            'caja' => $caja
        ];
    }

    public function open(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        $centro_id = $request->centro_id;
        $now = Carbon::now('America/Lima')->toDateTimeString();
        $total_start = 0;

        //CERRAR EL ANTERIOR
        $caja = Caja::where('centro_id', '=', $centro_id)
                    ->where('state', '=', 1)
                    ->orderBy('id', 'desc')->first();
        
        if ( $caja != null ) {
            $total_start = $caja->total_end;

            $caja->state = 0;
            $caja->save();
        }

        //ABRIR EL ACTUAL
        $caja = new Caja();
        $caja->centro_id = $centro_id;
        $caja->total_start = $total_start;
        $caja->total_end = $total_start;
        $caja->start = $now;
        $caja->end = substr($now, 0, 10).' 23:59:59';
        $caja->save();

        return $caja;
    }

    public function close(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        $now = Carbon::now('America/Lima')->toDateTimeString();
        $caja_id = $request->caja_id;

        //CERRAR EL ACTUAL
        $caja = Caja::findOrFail($caja_id);
        $caja->state = 0;
        $caja->end = $datetime;
        $caja->save();

        return $caja;
    }
}
