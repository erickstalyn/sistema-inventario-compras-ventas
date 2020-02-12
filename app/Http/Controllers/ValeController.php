<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vale;
use Carbon\Carbon;

class ValeController extends Controller {
    
    public function get(Request $request) {
        if ( !$request->ajax() ) return redirect('/');
        
        $cliente_id = $request->id;

        $state = 'error';

        $vale = Vale::select('id', 'monto', 'created_at')
                    ->where('persona_id', '=', $cliente_id)
                    ->where('venta_usada_id', '=', NULL)
                    ->where('updated_at', '=', NULL)->first();
        
        $state = 'success';

        return [
            'state' => $state,
            'vale' => $vale
        ];
    }

    public function listar(Request $request){

        if ( !$request->ajax() ) return redirect('/');

        $estado = $request->estado;
        $texto = $request->texto;
        $filas = $request->filas;
        $centro_id = $request->centro_id;
        $allMonth = $request->allMonth;

        $vales = Vale::select('vale.id', 'vale.monto', 'vale.created_at', 'vale.updated_at', 'vale.venta_generada_id', 'persona.tipo', 'persona.dni', 'persona.ruc', 'persona.razon_social', 'persona.nombres', 'persona.apellidos')
                    ->join('persona','vale.persona_id', '=', 'persona.id')
                    ->join('venta', 'venta.id', '=', 'vale.venta_generada_id')
                    ->where('venta.centro_id', '=', $centro_id)
                    ->where(function ($query) use ($allMonth) {
                        $mes = date('m');
                        if($allMonth != 1) $query->whereMonth('vale.created_at', $mes);
                    })
                    ->where(function ($query) use ($texto) {
                        if ( $texto != '' ) {
                            if(is_numeric($texto)){
                                $query->where('persona.dni', '=', $texto)
                                ->orWhere('persona.ruc', '=', $texto);
                            }else{
                                $query->where('persona.razon_social', 'like', '%'.$texto.'%')
                                ->orWhere('persona.nombres', 'like', '%'. $texto . '%')
                                ->orWhere('persona.apellidos', 'like', '%'. $texto . '%');
                            }
                        }
                    })
                    ->where(function ($query) use ($estado) {
                        if ( $estado != 3 ) {
                            if($estado == 1) $query->whereNotNull('vale.venta_usada_id'); //USADA
                            if($estado == 2) $query->whereNull('vale.venta_usada_id'); //SIN USAR
                        }
                    })
                    // ->orderBy('vale.id', 'desc')->get();
                    ->orderBy('vale.id', 'desc')->paginate($filas);
        
        return [
            'paginacion' => [
                'total' => $vales->total(),
                'currentPage' => $vales->currentPage(),
                'perPage' => $vales->perPage(),
                'lastPage' => $vales->lastPage(),
                'firstItem' => $vales->firstItem(),
                'lastItem' => $vales->lastItem()
            ],
            'vales' => $vales
        ];

    }
}
