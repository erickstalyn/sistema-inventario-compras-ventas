<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Convertidor extends Model
{

    private $unidades = NULL;
    private $conversiones = NULL;

    function __construct() {
        $this->unidades = self::getUnidades();
        $this->conversiones = self::getConversiones();
    }

    public function convertir($unidad_from, $unidad_to, $cantidad) {
        // VALIDACIONES

        // unidades y conversiones
        if ( $this->unidades == NULL || empty($this->unidades) ) return NULL;
        $unidades = $this->unidades;
        if ( $this->conversiones == NULL || empty($this->conversiones) ) return NULL;
        $conversiones = $this->conversiones;

        // parametros
        $found_from = false; $found_to = false; 
        $found_tipo_from = NULL; $found_tipo_to = NULL;

        for ($i = 0; $i < count($unidades); $i++) { 
            if ( $unidad_from == $unidades[$i]['unidad_nombre'] ) {
                $found_from = true;
                $found_tipo_from = $unidades[$i]['tipo_unidad_nombre'];
            } 
            if ( $unidad_to == $unidades[$i]['unidad_nombre'] ) {
                $found_to = true;
                $found_tipo_to = $unidades[$i]['tipo_unidad_nombre'];
            }
            if ( $found_from == true && $found_to == true ) break;
        }

        try {
            if ( $found_from != true || $found_to != true || $cantidad == NULL || $found_tipo_from != $found_tipo_to ) return NULL;
            if ( floatval($cantidad) < 0 ) return NULL;
        } catch (\Exception $e) {
            return NULL;
        }
        
        // CALCULAR CONVERSION
        $step = [
            'nombre' => $unidad_from,
            'valor' => floatval($cantidad)
        ];

        $step = self::nextStep($step, $conversiones, $unidad_to);
        
        foreach ($this->conversiones as $conversion) $conversion['revisado'] = 0;
        
        return $step['valor'];
    }
    private function nextStep($step, $conversiones, $unidad_to) {
        if ( $step['nombre'] == $unidad_to ) return $step;

        for ($i = 0; $i < count($conversiones); $i++) {
            if ( $conversiones[$i]['revisado'] == 0 ) {
                // ida
                if ( $conversiones[$i]['nombre_unidad_from'] == $step['nombre'] ) {
                    $conversiones[$i]['revisado'] = 1;

                    $new_step = [
                        'nombre' => $conversiones[$i]['nombre_unidad_to'],
                        'valor' => $step['valor'] / floatval($conversiones[$i]['factor'])
                    ];

                    if ( $new_step['nombre'] == $unidad_to ) {
                        $step = $new_step; break;
                    }

                    $new_step = self::nextStep($new_step, $conversiones, $unidad_to);
                    
                    if ( $new_step['nombre'] == $unidad_to ) {
                        $step = $new_step; break;
                    }
                }
                // vuelta
                if ( $conversiones[$i]['nombre_unidad_to'] == $step['nombre'] ) {
                    $conversiones[$i]['revisado'] = 1;

                    $new_step = [
                        'nombre' => $conversiones[$i]['nombre_unidad_from'],
                        'valor' => $step['valor'] * floatval($conversiones[$i]['factor'])
                    ];

                    if ( $new_step['nombre'] == $unidad_to ) {
                        $step = $new_step; break;
                    }

                    $new_step = self::nextStep($new_step, $conversiones, $unidad_to);
                    
                    if ( $new_step['nombre'] == $unidad_to ) {
                        $step = $new_step; break;
                    }
                }
            }
        }

        return $step;
    }

    public static function getTiposUnidad() {
        try {
            return TipoUnidad::select('nombre')->get();
        } catch (\Exception $e) {
            return NULL;
        }
    }
    public static function getUnidades() {
        try {
            return Unidad::join('tipo_unidad', 'tipo_unidad.id', '=', 'unidad.tipo_unidad_id')
                        ->select('unidad.nombre AS unidad_nombre', 'tipo_unidad.nombre AS tipo_unidad_nombre')
                        ->where('tipo_unidad.estado', '=', '1')
                        ->where('unidad.estado', '=', '1')->get();
        } catch (\Exception $e) {
            return NULL;
        }
    }
    public static function getConversiones() {
        try {
            return Conversion::join('unidad AS unidad_to', 'unidad_to.id', '=', 'conversion.unidad_to_id')
                        ->join('unidad AS unidad_from', 'unidad_from.id', '=', 'conversion.unidad_from_id')
                        ->select('unidad_from.nombre AS nombre_unidad_from', 'unidad_to.nombre AS nombre_unidad_to', 'factor', DB::raw('0 AS revisado'))->get();
        } catch (\Exception $e) {
            return NULL;
        }
    }
}
