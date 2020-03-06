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
        $total_start = 0;

        $state = 'transaction-start';
        $exception = NULL;

        try {
            DB::beginTransaction();

            // caja vacia antigua
            $step = 'caja-vacia-old';
                            
            $caja = Caja::where(function ($query) use ($date) {
                            $query->where('start', '=', NULL)
                                ->orWhere(DB::raw('CAST(start AS DATE)'), '=', $date);
                        })
                        ->where('centro_id', '=', $centro_id)
                        ->orderBy('start', 'desc')->first();

            if ( $caja != NULL ) {
                $state = 'transaction-success';
                $caja->getConceptos;
                return [
                    'step' => $step,
                    'state' => $state,
                    'caja' => $caja
                ];
            }

            // caja ultima
            $step = 'caja-last';
            $caja = Caja::where('start', '!=', NULL)
                        ->where('centro_id', '=', $centro_id)
                        ->orderBy('start', 'desc')->first();
            
            
            if ( $caja != NULL ) {
                if ( $caja->state == 1 ) {
                    $caja->state = 0;
                    $caja->end = substr($caja->start, 0, 10).' 23:59:59';
                    $caja->save();
                }
                $total_start = $caja->total_end;
            }

            // caja vacia nueva
            $step = 'caja-vacia-new';
            $caja = new Caja();
            $caja->total_start = $total_start;
            $caja->total_end = $total_start;
            $caja->total_ingreso = 0;
            $caja->total_egreso = 0;
            $caja->state = 0;
            $caja->centro_id = $centro_id;
            $caja->save();

            $caja->getConceptos;
            
            DB::commit();
            $state = 'transaction-success';
        } catch (Exception $e) {
            DB::rollback();
            $state = 'transaction-exception';
            $exception = $e;
        }

        return [
            'step' => $step,
            'state' => $state,
            'exception' => $exception,
            'caja' => $caja
        ];
    }

    public function open(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        $caja_id = $request->caja_id; //id de la caja
        $datetime = Carbon::now('America/Lima')->toDateTimeString();

        $caja = NULL;
        $exception = NULL;
        $message = NULL;
        $step = NULL;

        try {
            DB::beginTransaction();
            $state = 'transaction-start';

            //ABRIR EL ACTUAL
            $caja = Caja::findOrFail($caja_id);

            if ( $caja->state == 0 ) {
                $caja->state = 1;
                if ( $caja->start == NULL ) $caja->start = $datetime;
                $caja->end = NULL;
                $caja->save();
            } else {
                $message = 'try of open box failed because box is open';
            }
            
            DB::commit();
            $state = 'transaction-success';
        } catch (Exception $e) {
            DB::rollback();
            $state = 'transaction-exception';
            $exception = $e;
        }

        return [
            'state' => $state,
            'step' => $step,
            'exception' => $exception,
            'message' => $message,
            'caja' => $caja
        ];
    }

    public function close(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        $datetime = Carbon::now('America/Lima')->toDateTimeString();
        $caja_id = $request->caja_id;

        $state = 'start';
        $exception = NULL;
        $step = NULL;
        $message = NULL;

        try {
            DB::beginTransaction();
            $state = 'transaction-start';
            //CERRAR EL ACTUAL
            $caja = Caja::findOrFail($caja_id);

            if ( $caja->state == 1 ) {
                $caja->state = 0;
                $caja->end = $datetime;
                $caja->save();
            } else {
                $message = 'try of close the box failed because the box is close';
            }

            DB::commit();
            $state = 'transaction-success';
        } catch (Exception $e) {
            DB::rollback();
            $state = 'transaction-exception';
            $exception = $e;
        }
        
        return [
            'caja' => $caja,
            'state' => $state,
            'message' => $message,
            'step' => $step,
            'exception' => $exception
        ];
    }

    public function search(Request $request){
        $state = 'transaction-validate';
        $step = NULL;
        $message = NULL;
        
        //Validacion: el request debe ser ajax
        $step = 'validacion ajax';
        if ( !$request->ajax() ) return redirect('/');

        $step = 'datos del request';
        $date = $request->date;
        $date_today = Carbon::now('America/Lima')->toDateString();
        $center_id = $request->center_id;
        
        $state = 'transaction-select';

        $step = 'query a la db';
        $box = Caja::select('id', 'total_start', 'total_end AS total_finish', 'total_ingreso AS total_ingress', 'total_egreso AS total_egress', 'state', 'start AS start_at', 'end AS finish_at')
                    ->where(DB::raw('CAST(start AS DATE)'), '=', $date)
                    ->where(DB::raw('CAST(start AS DATE)'), '!=', $date_today)
                    ->where('centro_id', '=', $center_id)->first();
        
        $step = 'verificacion de que de encontro la caja';
        if ( $box != null ) {
            $step = 'buscando los conceptos de esa caja';
            $box->getConceptos;
            $step = 'conceptos encontrados';
            $state = 'transaction-success';
        } else {
            $step = 'sin caja';
            $state = 'transaction-not-found';
        }

        return [
            'box' => $box,
            'state' => $state,
            'step' => $step,
            'message' => $message
        ];
    }

}
