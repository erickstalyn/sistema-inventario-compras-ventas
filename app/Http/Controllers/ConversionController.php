<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoUnidad;
use App\Unidad;
use App\Conversion;

class ConversionController extends Controller
{
    public static function convertir($unidad_from, $unidad_to, $cantidad) {
        // validaciones
        $unidades = Unidad::join('tipo_unidad', 'tipo_unidad.id', '=', 'unidad.tipo_unidad_id')
                        ->select('unidad.nombre AS unidad_nombre', 'tipo_unidad.nombre AS tipo_unidad_nombre')
                        ->where('tipo_unidad.estado', '=', '1')
                        ->where('unidad.estado', '=', '1')->get();
        $found_from = false; $found_to = false; $found_tipo_from = NULL; $found_tipo_to = NULL;
        for ($i=0; $i < count($unidades); $i++) { 
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
        if ( $found_from != true || $found_to != true || $cantidad == NULL || $cantidad < 0 || $found_tipo_from != $found_tipo_to ) return NULL;

        // conversiones
        $conversiones = Conversion::join('unidad AS unidad_to', 'unidad_to.id', '=', 'conversion.unidad_to_id')
                                ->join('unidad AS unidad_from', 'unidad_from.id', '=', 'conversion.unidad_from_id')
                                ->select('unidad_from.nombre AS nombre_unidad_from', 'unidad_to.nombre AS nombre_unidad_to', 'factor', 'false AS revisado');
        
        $ruta = [
            [
                'nombre' => $unidad_from,
                'valor' => $cantidad
            ]
        ];
        $resultado = findConver($ruta, $conversiones, $unidad_to);
        if ( $resultado['found'] == false ) return NULL;

        $ruta = $resultado['ruta'];

        return $ruta[count($ruta)-1]['valor'];
    }

    private static function findConver($ruta, $conversiones, $unidad_to) {
        $etapa = $ruta[count($ruta)-1];

        if ( $etapa['nombre'] == $unidad_to ) {
            return [
                'ruta' => $ruta,
                'found' => true
            ];
        }

        $found = false;

        for ($i=0; $i < count($conversiones); $i++) { 
            if ( $conversiones[$i]['revisado'] == false ) {
                // ida
                if ( $conversiones[$i]['nombre_unidad_from'] == $etapa['nombre'] ) {
                    $conversiones[$i]['revisado'] = true;
                    $ruta[] = [
                        'nombre' => $conversiones[$i]['nombre_unidad_to'],
                        'valor' => $etapa['valor'] * $conversiones[$i]['factor']
                    ];

                    if ( $conversiones[$i]['nombre_unidad_to'] == $unidad_to ) {
                        $found = true;
                        break;
                    } else {
                        $resultado = findConver($ruta, $conversiones, $unidad_to);
                        if ( $resultado['found'] == true ) {
                            $found = true; break;
                        }
                    }
                }
                // vuelta
                if ( $conversiones[$i]['nombre_unidad_to'] == $etapa['nombre'] ) {
                    $conversiones[$i]['revisado'] = true;
                    $ruta[] = [
                        'nombre' => $conversiones[$i]['nombre_unidad_to'],
                        'valor' => $etapa['valor'] / $conversiones[$i]['factor']
                    ];

                    if ( $conversiones[$i]['nombre_unidad_from'] == $unidad_to ) {
                        $found = true;
                        break;
                    } else {
                        $resultado = findConver($ruta, $conversiones, $unidad_to);
                        if ( $resultado['found'] == true ) {
                            $found = true; break;
                        }
                    }
                }
            }
            
        }

        return [
            'ruta' => $ruta,
            'found' => $found
        ];
    }
}
