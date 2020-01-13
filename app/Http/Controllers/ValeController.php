<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vale;

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

}
