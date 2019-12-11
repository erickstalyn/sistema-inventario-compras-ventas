<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Centro;

class CentroController extends Controller
{
    public function selectAlmacen(Request $request){
        if ( !$request->ajax() ) return redirect('/');
        $almacenes = Centro::select('id', 'nombre')
                            ->where('tipo','=','A')
                            ->orderBy('nombre', 'asc')->get();
        return $almacenes;
    }

    public function selectCentro(Request $request){
        if(!$request->ajax()) return redirect('/');
        $idCentro = $request->idCentro;

        $centros = Centro::select('id', 'nombre')
                            ->where('id', '<>', $idCentro)
                            ->orderby('id', 'asc')->get();
        return $centros;

    }
}
