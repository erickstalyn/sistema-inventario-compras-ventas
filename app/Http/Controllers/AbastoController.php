<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Abasto;
use App\Persona;
use Exception;

class AbastoController extends Controller
{
    public function listar(Request $request){
        // if ( !$request->ajax() ) return redirect('/') ;
        
        $estado = $request->estado;
        $texto = $request->texto;
        $filas = $request->filas;
        $ordenarPor = $request->ordenarPor;
        $orden = $request->orden;
        $hoy = Carbon::now('America/Lima')->toDateString();
        //Fechas
        $dia = $request->dia;
        $mes = $request->mes;
        $year = $request->year;

        $abastos = Abasto::select('abasto.id as id','persona.id as proveedor_id','proveedor_nombre', 'centro_to_id', 'centro.nombre as nombre_centro', 
                        'abasto.created_at as fecha_envio', 'abasto.total as costo_total',
                        'abasto.tipo as tipo_abasto', 'envio.estado as estado_envio')
                        ->join('persona', 'abasto.proveedor_id', '=', 'persona.id')
                        ->leftjoin('envio', 'abasto.id', '=', 'envio.abasto_id')
                        ->leftjoin('centro', 'envio.centro_to_id', 'centro.id')
                            ->where(function ($query) use ($texto) {
                                if ( $texto != '' ) {
                                    $query->where('persona.razon_social', 'like', '%'.$texto.'%')
                                        ->orWhere('abasto.proveedor_nombre', 'like', '%'. $texto . '%');
                                }
                            })
                            ->where(function ($query) use ($estado) {
                                if ( $estado != 2 ) {
                                    $query->where('envio.estado', '=', $estado);
                                }
                            })
                            ->where(function ($query) use ($dia, $mes, $year) {
                                if($dia != '' && $mes != '' && $year != ''){//todos los campos llenos
                                    $query->whereDay('abasto.created_at', $dia)
                                        ->whereMonth('abasto.created_at', $mes)
                                        ->whereYear('abasto.created_at', $year);
                                }else if($dia != '' && $mes != ''){// dia y mes llenos
                                    $query->whereDay('abasto.created_at', $dia)
                                        ->whereMonth('abasto.created_at', $mes);
                                }else if($dia != '' && $year != ''){//dia y año lleno
                                    $query->whereDay('abasto.created_at', $dia)
                                        ->whereYear('abasto.created_at', $year);
                                }else if($mes != '' && $year != ''){//mes y año lleno
                                    $query->whereMonth('abasto.created_at', $mes)
                                        ->whereYear('abasto.created_at', $year);
                                }else if($dia != ''){//dia lleno
                                    $query->whereDay('abasto.created_at', $dia);
                                }else if($mes != ''){//mes lleno
                                    $query->whereMonth('abasto.created_at', $mes);
                                }else if($year != ''){//año lleno
                                    $query->whereYear('abasto.created_at', $year);
                                }else{
                                }
                            })
                            ->where('abasto.administrador_id', '<>', null)
                            // ->orderBy('abasto.id', 'asc')->get();
                            ->orderBy($ordenarPor, $orden)->paginate($filas);

        return [
            'paginacion' => [
                'total' => $abastos->total(),
                'currentPage' => $abastos->currentPage(),
                'perPage' => $abastos->perPage(),
                'lastPage' => $abastos->lastPage(),
                'firstItem' => $abastos->firstItem(),
                'lastItem' => $abastos->lastItem()
            ],
            'abastos' => $abastos
        ];
    }

    public function agregar(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        try {
            DB::beginTransaction();

            //Agrego la produccion
            $proveedor = $request->proveedor;
            $abasto = new Abasto();//AQUI ME QUEDE
            $abasto->total = $request->total;
            $abasto->tipo = $request->tipo;
            //Verifico si existe el proveedor
            if($proveedor['id'] == 0){ //No existe el proveedor
                //Insertamos al proveedor
                $persona = new Persona();
                if(strlen($proveedor['documento']) == 8){
                    
                    $persona->dni = $proveedor['documento'];
                    $persona->nombres = $proveedor['nombres'];
                    $persona->apellidos = $proveedor['apellidos'];

                    $abasto->proveedor_nombre = $proveedor['nombres'] . ' ' . $proveedor['apellidos'];
                }else{
                    $persona->ruc = $proveedor['documento'];
                    $persona->razon_social = $proveedor['razon_social'];

                    $abasto->proveedor_nombre = $proveedor['razon_social'];
                }
                $persona->save();
                $abasto->proveedor_id = $persona->id;
            }else{ //Ya existe el proveedor
                $abasto->proveedor_id = $proveedor['id'];
                if(strlen($proveedor['documento']) == 8){
                    $abasto->proveedor_nombre = $proveedor['nombres'] . ' ' . $proveedor['apellidos'];
                }else{
                    $abasto->proveedor_nombre = $proveedor['razon_social'];
                }

            }

            $abasto->save();

            //Insertamos los datos del detalle de produccion
            // $listaDetalleProduccion = $request->listaDetalleProduccion;

            // foreach($listaDetalleProduccion as $ep => $det){
            //     $detalle = new Detalle_produccion();
            //     $detalle->nombre_producto = $det['nombre'];
            //     $detalle->costo_produccion = $det['costo_produccion'];
            //     $detalle->cantidad = $det['cantidad'];
            //     // $detalle->subtotal = floatval($det['cantidad']) * floatval($det['costo_produccion']);
            //     $detalle->subtotal = $det['subtotal'];
            //     $detalle->producto_id = $det['id'];
            //     $detalle->produccion_id = $produccion->id;
            //     $detalle->save();
            // }

            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
        }

    }
}
