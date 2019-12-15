<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Venta;

class VentaController extends Controller {
    
    public function listar(Request $request) {
        if ( $request->ajax() ) return redirect('/');

        $type = $request->type;
        $text = $request->text;
        $rows = $request->rows;

        $ventas = Venta::select('venta.id', 'venta.codigo', 'venta.tipo', 'venta.total', 'venta.total_faltante', 'venta.created_at', 
                                'persona.dni', 'persona.ruc', 'persona.nombres', 'persona.apellidos', 'persona.razon_social')
                    ->join('persona', 'persona.id', '=', 'venta.cliente_id')
                    ->where(function ($query) use ($text) {
                        if ( $text != '' ) {
                            $query->where('persona.nombres', 'like', '%'.$text.'%')
                                ->orWhere('persona.apellidos', 'like', '%'.$text.'%')
                                ->orWhere('persona.razon_social', 'like', '%'.$text.'%')
                                ->orWhere($codigo, '=', $codigo);
                        }
                    })
                    ->where(function ($query) use ($type) {
                        if ( $type != 2 ) {
                            $query->where('venta.tipo', '=', $type);
                        }
                    })
                    ->orderBy('id', 'desc')->paginate($rows);
        
        return [
            'pagination' => [
                'total' => $ventas->total(),
                'currentPage' => $ventas->currentPage(),
                'perPage' => $ventas->perPage(),
                'lastPage' => $ventas->lastPage(),
                'firstItem' => $ventas->firstItem(),
                'lastItem' => $ventas->lastItem()
            ],
            'ventas' => $ventas
        ];
    }
}
