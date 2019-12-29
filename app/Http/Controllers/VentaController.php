<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Venta;
use App\Detalle_venta;
use App\Persona;
use App\Pago;
use Carbon\Carbon;
use Exception;

class VentaController extends Controller {
    
    public function listar(Request $request) {
        if ( !$request->ajax() ) return redirect('/');

        $type = $request->type;
        $text = $request->text;
        $rows = $request->rows;
        $centro_id = $request->centro_id;
        $dia = $request->dia;
        $mes = $request->mes;
        $year = $request->year;

        $ventas = Venta::select('venta.id', 'venta.codigo', 'venta.tipo', 'venta.total', 'venta.total_faltante', 'venta.created_at', 
                                'persona.id as cliente_id', 'persona.dni', 'persona.ruc', 'persona.nombres', 'persona.apellidos', 'persona.razon_social', 'persona.tipo as cliente_tipo')
                    ->leftJoin('persona', 'persona.id', '=', 'venta.cliente_id')
                    ->where(function ($query) use ($text, $dia, $mes, $year) {
                        if ( strlen($text) == 15 && is_numeric($text) ) {
                            $query->where('codigo', '=', $text);
                        } else {
                            $query->where(function ($subquery) use ($text, $dia, $mes, $year) {
                                    if ( $text != '' ) {
                                        $subquery->where('nombres', 'like', '%'.$text.'%')
                                                ->orWhere('apellidos', 'like', '%'.$text.'%')
                                                ->orWhere('razon_social', 'like', '%'.$text.'%')
                                                ->orWhere('codigo', '=', $text);
                                    }
                                })
                                ->where(function ($subquery) use ($dia, $mes, $year) {
                                    if ( $dia != '' ) $subquery->whereDay('venta.created_at', $dia);
                                    if ( $mes != '' ) $subquery->whereMonth('venta.created_at', $mes);
                                    if ( $year != '' ) $subquery->whereYear('venta.created_at', $year);
                                });
                        }
                    })
                    ->where(function ($query) use ($type) {
                        if ( $type == 1) {
                            $query->where(DB::raw('substring(venta.tipo, 1, 1)'), '=', 1);
                        } else if ( $type == 2 ) {
                            $query->where(DB::raw('substring(venta.tipo, 1, 1)'), '=', 2)
                                ->orWhere(DB::raw('substring(venta.tipo, 1, 1)'), '=', 3);
                        }
                    })
                    // ->where(function ($query) use ($dia, $mes, $year) {
                    //     if($dia != '' && $mes != '' && $year != ''){//todos los campos llenos
                    //         $query->whereDay('venta.created_at', $dia)
                    //             ->whereMonth('venta.created_at', $mes)
                    //             ->whereYear('venta.created_at', $year);
                    //     }else if($dia != '' && $mes != ''){// dia y mes llenos
                    //         $query->whereDay('venta.created_at', $dia)
                    //             ->whereMonth('venta.created_at', $mes);
                    //     }else if($dia != '' && $year != ''){//dia y año lleno
                    //         $query->whereDay('venta.created_at', $dia)
                    //             ->whereYear('venta.created_at', $year);
                    //     }else if($mes != '' && $year != ''){//mes y año lleno
                    //         $query->whereMonth('venta.created_at', $mes)
                    //             ->whereYear('venta.created_at', $year);
                    //     }else if($dia != ''){//dia lleno
                    //         $query->whereDay('venta.created_at', $dia);
                    //     }else if($mes != ''){//mes lleno
                    //         $query->whereMonth('venta.created_at', $mes);
                    //     }else if($year != ''){//año lleno
                    //         $query->whereYear('venta.created_at', $year);
                    //     }else{
                    //     }
                    // })
                    ->where('centro_id', '=', $centro_id)
                    ->orderBy('id', 'desc')->paginate($rows);
        
        return [
            'pagination' => [
                'total' => $ventas->total(),
                'currentPage' => $ventas->currentPage(),
                'perPage' => $ventas->perPage(),
                'lastPage' => $ventas->lastPage(),
                'firstItem' => $ventas->firstItem(),
                'lastItem' => $ventas->lastItem()
            ],
            'ventas' => $ventas
        ];
    }

    public function agregar(Request $request) {
        if ( !$request->ajax() ) return redirect('/');
        
        $now = Carbon::now('America/Lima')->toDateTimeString();
        
        try {
            DB::beginTransaction();
            
            //cliente
            $cliente = $request->cliente;
            if ( $cliente['id'] > 0 ) {
                $persona = Persona::findOrFail($cliente['id']);
                $persona->cliente = 1;
                $persona->save();
            } else if ( $cliente['id'] == 0 ){
                $persona = new Persona();
                $persona->cliente = 1;
                if ( strlen($cliente['documento']) == 8 ){
                    $persona->dni = $cliente['documento'];
                    $persona->nombres = mb_convert_case($cliente['nombres'], MB_CASE_TITLE, "UTF-8");
                    $persona->apellidos = mb_convert_case($cliente['apellidos'], MB_CASE_TITLE, "UTF-8");
                    $persona->tipo = 'P';
                }else{
                    $persona->ruc = $cliente['documento'];
                    $persona->razon_social = $cliente['razon_social'];
                    $persona->tipo = 'E';
                }
                $persona->save();
            }

            {
                $data = array_merge(explode('-', explode(' ', $now)[0]), explode(':', explode(' ', $now)[1]));
                $codigo = '';
                for ($i=0; $i < count($data); $i++) $codigo .= $data[$i];
                $codigo .= rand(0, 9);
            }

            //venta
            $venta = new Venta();
            $venta->centro_id = $request->centro_id;
            $venta->tipo = $request->tipo_pago.$request->tipo_precio;
            $venta->total = $request->total;
            if ( $request->tipo_pago == 2 || $request->tipo_pago == 3 ) $venta->total_faltante = $request->total;
            $venta->cliente_id = $cliente['id']>=0?$persona->id:NULL;
            $venta->codigo = $codigo;
            $venta->created_at = $now;
            $venta->updated_at = NULL;
            $venta->save();

            //pago
            if ( ($request->tipo_pago == 2 || $request->tipo_pago == 3) && $request->pago_monto > 0 ){
                $pago = new Pago();
                $pago->monto = $request->pago_monto;
                $pago->venta_id = $venta->id;
                $pago->created_at = $now;
                $pago->save();
            }

            //lista de detalles
            foreach($request->listaDetalle as $ep => $det){
                $detalle = new Detalle_venta();
                $detalle->detalle_producto_id = $det['detalle_producto_id']; //AQUI ESTA EL PROBLEMA
                $detalle->venta_id = $venta->id;
                $detalle->nombre_producto = $det['nombre_producto'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $request->tipo_precio==1?$det['precio_menor']:$det['precio_mayor'];
                $detalle->subtotal = $det['subtotal'];
                $detalle->save();
            }

            DB::commit();
            $error = NULL;
        } catch (Exception $e) {
            $error = $e;
            DB::rollback();
        }

        return [
            'estado' => $error==NULL?0:1,
            'error' => $error
        ];
    }
}
