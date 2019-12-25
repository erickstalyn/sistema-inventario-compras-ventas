<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Produccion;
use App\Producto;
use App\Exception;
use App\Detalle_produccion;
use App\Centro;
use DB;

class ProduccionController extends Controller
{
    public function listar(Request $request){
        if ( !$request->ajax() ) return redirect('/') ;
        
        $estado = $request->estado;
        $texto = $request->texto;
        $filas = $request->filas;
        $ordenarPor = $request->ordenarPor;
        $orden = $request->orden;
        $hoy = Carbon::now('America/Lima')->toDateString();
        $idAlmacen = $request->idAlmacen;
        //Fechas
        $dia = $request->dia;
        $mes = $request->mes;
        $year = $request->year;

        $producciones = Produccion::select('id', 'total','fecha_inicio', 'fecha_programada', 'fecha_fin')
                            ->where(function ($query) use ($estado, $hoy) {
                                if ( $estado != 3 ) {
                                    if($estado == 2) {//Sin iniciar
                                        $query->whereDate('fecha_inicio', '>', $hoy);
                                    }else if($estado == 1){//En proceso
                                        $query->where('fecha_fin', null)->whereDate('fecha_inicio', '<=', $hoy);
                                    }else{//Finalizado
                                        $query->whereDate('fecha_fin', '<>' ,'null');
                                    }
                                }
                            })
                            ->where(function ($query) use ($dia, $mes, $year) {
                                if($dia != '' && $mes != '' && $year != ''){//todos los campos llenos
                                    $query->whereDay('fecha_inicio', $dia)
                                        ->whereMonth('fecha_inicio', $mes)
                                        ->whereYear('fecha_inicio', $year);
                                }else if($dia != '' && $mes != ''){// dia y mes llenos
                                    $query->whereDay('fecha_inicio', $dia)
                                        ->whereMonth('fecha_inicio', $mes);
                                }else if($dia != '' && $year != ''){//dia y año lleno
                                    $query->whereDay('fecha_inicio', $dia)
                                        ->whereYear('fecha_inicio', $year);
                                }else if($mes != '' && $year != ''){//mes y año lleno
                                    $query->whereMonth('fecha_inicio', $mes)
                                        ->whereYear('fecha_inicio', $year);
                                }else if($dia != ''){//dia lleno
                                    $query->whereDay('fecha_inicio', $dia);
                                }else if($mes != ''){//mes lleno
                                    $query->whereMonth('fecha_inicio', $mes);
                                }else if($year != ''){//año lleno
                                    $query->whereYear('fecha_inicio', $year);
                                }else{
                                }
                            })
                            ->where('produccion.almacen_id', '=', $idAlmacen)
                            ->orderBy('produccion.id', 'desc')->paginate($filas);

        return [
            'paginacion' => [
                'total' => $producciones->total(),
                'currentPage' => $producciones->currentPage(),
                'perPage' => $producciones->perPage(),
                'lastPage' => $producciones->lastPage(),
                'firstItem' => $producciones->firstItem(),
                'lastItem' => $producciones->lastItem()
            ],
            'producciones' => $producciones
        ];
    }

    public function agregar(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        try {
            DB::beginTransaction();

            //Agrego la produccion
            $produccion = new Produccion();
            $produccion->total = $request->total;
            $produccion->fecha_inicio = $request->fecha_inicio;
            $produccion->fecha_programada = $request->fecha_programada;
            $produccion->almacen_id = $request->almacen_id;
            $produccion->save();

            //Insertamos los datos del detalle de produccion
            $listaDetalleProduccion = $request->listaDetalleProduccion;

            foreach($listaDetalleProduccion as $ep => $det){
                $detalle = new Detalle_produccion();
                $detalle->nombre_producto = $det['nombre_producto'];
                $detalle->costo_produccion = $det['costo_produccion'];
                $detalle->cantidad = $det['cantidad'];
                // $detalle->subtotal = floatval($det['cantidad']) * floatval($det['costo_produccion']);
                $detalle->subtotal = $det['subtotal'];
                $detalle->producto_id = $det['producto_id'];
                $detalle->produccion_id = $produccion->id;
                $detalle->save();
            }

            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
        }

    }

    public function editar(Request $request) {
        // if ( !$request->ajax() ) return redirect('/');

        $produccion = Produccion::findOrFail($request->id);
        $produccion->total = $request->total;
        $produccion->fecha_inicio = $request->fecha_inicio;
        $produccion->fecha_programada = $request->fecha_programada;
        $produccion->almacen_id = $request->almacen_id;
        $produccion->save();

        $lista = $request->listaDetalleProduccion;
        try {
            DB::beginTransaction();

            for ($i = 0; $i < count($lista); $i++) {
                if ( $lista[$i]['id'] == 0 ) {
                    $detalleProduccion = new Detalle_produccion();
                    $detalleProduccion->nombre_producto = $lista[$i]['nombre_producto'];
                    $detalleProduccion->costo_produccion = $lista[$i]['costo_produccion'];
                    $detalleProduccion->cantidad = $lista[$i]['cantidad'];
                    $detalleProduccion->subtotal = $lista[$i]['subtotal'];
                    $detalleProduccion->producto_id = $lista[$i]['producto_id'];
                    $detalleProduccion->produccion_id = $produccion->id;
                    $detalleProduccion->save();
                } else if ( $lista[$i]['estado'] == 1 ) {
                    $detalleProduccion = Detalle_produccion::findOrFail($lista[$i]['id']);
                    $detalleProduccion->nombre_producto = $lista[$i]['nombre_producto'];
                    $detalleProduccion->costo_produccion = $lista[$i]['costo_produccion'];
                    $detalleProduccion->cantidad = $lista[$i]['cantidad'];
                    $detalleProduccion->subtotal = $lista[$i]['subtotal'];
                    $detalleProduccion->producto_id = $lista[$i]['producto_id'];
                    $detalleProduccion->produccion_id = $produccion->id;
                    $detalleProduccion->save();
                } else {
                    Detalle_produccion::where('id', '=', $lista[$i]['id'])->delete();
                }
            }
            DB::commit();
            $error = NULL;
        } catch (Exception $e) {
            DB::rollback();
            $error = $e;
        }

        return [
            'estado' => $error==NULL?1:0,
            'error' => $error
        ];
    }

    public function getDetalles(Request $request){
        if ( !$request->ajax() ) return redirect('/') ;
        $detalles = Produccion::findOrFail($request->id)->getDetalles;
        return $detalles;
    }

    public function finalizar(Request $request){
        try {
            DB::beginTransaction();

            $produccion = Produccion::findOrFail($request->id);
            $produccion->fecha_fin = Carbon::now('America/Lima')->toDateTimeString();
            $produccion->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }
    }

    public function anular(Request $request){
        try {
            DB::beginTransaction();

            $produccion = Produccion::findOrFail($request->id);
            $produccion->delete();
            DB::commit();
        } catch (Exception $e) {
            echo($e);
            DB::rollback();
        }
    }
}
