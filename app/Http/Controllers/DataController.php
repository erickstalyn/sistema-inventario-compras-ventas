<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Data;

class DataController extends Controller{
    
    public function selectSize(Request $request){
        $tamaños = Data::select('nombre')
                    ->where('tipo', '=', 'T')->get();

        return $tamaños;
    }
    
    public function selectColor(Request $request){
        $colores = Data::select('nombre')
                    ->where('tipo', '=', 'C')->get();

        return $colores;
    }

    public function selectUnidad(Request $request){
        $tipos = Data::select('id', 'tipo', 'subtipo','nombre')
                            ->where('tipo','=','U')
                            ->orderBy('id', 'asc')->get();
        return $tipos;
    }
}
