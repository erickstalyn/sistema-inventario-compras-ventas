<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\TipoUnidad;
use App\Unidad;
use App\Conversion;
use App\Convertidor;

class UnidadController extends Controller
{
    public function getConvertidorData(Request $request){
        if ( !$request->ajax() ) return NULL;

        try {
            $unidades = Convertidor::getUnidades();
            $conversiones = Convertidor::getConversiones();

            return [
                'success' => true,
                'unidades' => $unidades,
                'conversiones' => $conversiones
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'exception' => $e
            ];
        }
    }
    public function getUnidades(Request $request){
        if ( !$request->ajax() ) return NULL;

        try {
            $unidades = Convertidor::getUnidades();

            return [
                'success' => true,
                'unidades' => $unidades
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'exception' => $e
            ];
        }
    }
    public function getTiposUnidad(Request $request){
        if ( !$request->ajax() ) return NULL;

        try {
            $tipos_unidad = Convertidor::getTiposUnidad();

            return [
                'success' => true,
                'tipos_unidad' => $tipos_unidad
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'exception' => $e
            ];
        }
    }

    public function calculateConversion(Request $request) {
        if ( !$request->ajax() ) return NULL;

        return (new Convertidor())->convertir($request->unidad_from, $request->unidad_to, $request->cantidad);
    }
}
