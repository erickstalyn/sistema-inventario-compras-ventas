<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Producto;
use App\Persona;
use App\Centro;

class LibreriaController extends Controller
{
    public function getProductoFiltrado(Request $request){
        if ( !$request->ajax() ) return redirect('/');
        $texto = $request->texto;

        $productos = Producto::select('id','nombre', 'stock', 'costo_produccion', 'codigo')
                            ->where(function ($query) use ($texto) {
                                if ( $texto != '' ) {
                                    $query->where('nombre', 'like', $texto . '%')
                                        ->orWhere('codigo', '=', $texto);
                                }
                            })
                            ->orderBy('nombre', 'asc')->get();
        return [
            'productos' => $productos
        ];
    }

    public function getPersona(Request $request){
        // if ( !$request->ajax() ) return redirect('/');
        $documento = $request->documento;

        $persona = Persona::select('id', 'nombres', 'apellidos', 'razon_social')
                            ->where('dni', '=', $documento)
                            ->orWhere('ruc', '=', $documento)
                            ->get();
        return [
            'persona' => $persona
        ];
    }

    public function selectAlmacen(Request $request){
        if ( !$request->ajax() ) return redirect('/');
        $almacenes = Centro::select('id', 'nombre',)
                            ->where('tipo','=','A')
                            ->orderBy('nombre', 'asc')->get();
        return $almacenes;
    }
}
