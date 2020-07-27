<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoUnidad;
use App\Unidad;

class ConvertidorController extends Controller
{
    public function getTipoUnidadAll($request){
        return TipoUnidad::all();
    }

    public function getUnidadAll(){
        return Unidad::all();
    }

    public function calcular($request) {
        return ConversionController::convertir($request->unidad_from, $request->unidad_to, $request->cantidad);
    }
}
