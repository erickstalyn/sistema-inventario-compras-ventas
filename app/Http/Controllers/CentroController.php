<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Centro;

class CentroController extends Controller
{
    public function selectAlmacen(Request $request){
        if ( !$request->ajax() ) return redirect('/');
        $almacen_id = $request->almacen_id;
        $almacenes = Centro::select('id', 'nombre',)
                            ->where('tipo','=','A')
                            ->where(function ($query) use ($almacen_id) {
                                if ( $almacen_id != '' ) {
                                    $query->where('id', '<>', $almacen_id);
                                }
                            })
                            ->orderBy('nombre', 'asc')->get();
        return $almacenes;
    }
}
