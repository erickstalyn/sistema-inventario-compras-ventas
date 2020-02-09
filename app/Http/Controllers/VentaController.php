<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Venta;
use App\Detalle_venta;
use App\Persona;
use App\Pago;
use App\Vale;
use Carbon\Carbon;
use Exception;
use App\Usuario;
use App\Notifications\NotifyAdmin;

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

        $ventas = Venta::select('venta.id', 'venta.codigo', 'venta.tipo', 'venta.total', 'venta.total_faltante', 'venta.total_descuento', 'venta.total_venta', 'venta.created_at', 
                                'persona.id as cliente_id', 'persona.dni', 'persona.ruc', 'persona.nombres', 'persona.apellidos', 'persona.razon_social', 'persona.tipo as cliente_tipo',
                                'vg.id as vale_generada_id', 'vg.monto as vale_generada_monto', 'vg.created_at as vale_generada_created_at',
                                'vu.id as vale_usada_id', 'vu.monto as vale_usada_monto', 'vu.created_at as vale_usada_created_at')
                    ->leftJoin('persona', 'persona.id', '=', 'venta.cliente_id')
                    ->leftJoin('vale as vg', 'vg.venta_generada_id', '=', 'venta.id')
                    ->leftJoin('vale as vu', 'vu.venta_usada_id', '=', 'venta.id')
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
        $dataVenta = $request->dataVenta;
        $dataValeU = $request->dataValeU;
        $dataCliente = $request->dataCliente;
        $dataPago = $request->dataPago;
        $listDetalle = $request->listDetalle;

        $state = 'error';
        $exception = NULL;

        try {
            DB::beginTransaction();
            
            //cliente
            if ( $dataCliente['id'] != null ) {
                if ( $dataCliente['id'] > 0 ) { //existe
                    $persona = Persona::findOrFail($dataCliente['id']);
                    $persona->cliente = 1;
                    $persona->save();
                } else { //excepciones
                    $dataCliente['id'] = NULL;
                }
            } else if ( $dataCliente['documento'] != NULL ) { //nuevo
                $persona = new Persona();
                $persona->cliente = 1;
                if ( strlen($dataCliente['documento']) == 8 ){
                    $persona->dni = $dataCliente['documento'];
                    $persona->nombres = mb_convert_case($dataCliente['nombres'], MB_CASE_TITLE, "UTF-8");
                    $persona->apellidos = mb_convert_case($dataCliente['apellidos'], MB_CASE_TITLE, "UTF-8");
                    $persona->tipo = 'P';
                } else {
                    $persona->ruc = $dataCliente['documento'];
                    $persona->razon_social = $dataCliente['razon_social'];
                    $persona->tipo = 'E';
                }
                $persona->save();
                $dataCliente['id'] = $persona->id;
            }
            
            {
                $data = array_merge(explode('-', explode(' ', $now)[0]), explode(':', explode(' ', $now)[1]));
                $codigo = '';
                for ($i=0; $i < count($data); $i++) $codigo .= $data[$i];
                $codigo .= rand(0, 9);
            }

            //venta
            $venta = new Venta();
            $venta->centro_id = $dataVenta['centro_id'];    // centro_id
            $venta->tipo = $dataVenta['tipo_pago'].$dataVenta['tipo_entrega'].$dataVenta['tipo_precio'];    // tipo
            $venta->total = $dataVenta['total'];    // total
            $venta->total_venta = $dataVenta['total_venta'];    //total_venta
            $venta->total_descuento = $dataVenta['total_descuento'];    // total_descuento
            $venta->total_faltante = $dataVenta['total_faltante'];  // total_faltante
            $venta->cliente_id = $dataCliente['id'];    // cliente_id
            $venta->codigo = $codigo;   // codigo
            $venta->created_at = $now;  // created_at
            $venta->updated_at = NULL;  // updated_at
            $venta->save();

            //vale
            if ( $dataValeU['id'] != null ) {
                if ( $dataValeU['id'] > 0 ) {
                    $vale = Vale::findOrFail($dataValeU['id']);
                    $vale->venta_usada_id = $venta->id; // venta_usada_id
                    $vale->updated_at = $now;   // updated_at
                    $vale->save();
                }
            }

            //pago
            if ( $dataVenta['tipo_pago'] == '2' && $dataPago['monto'] != NULL ){
                if ( $dataPago['monto'] > 0 ) {
                    $pago = new Pago();
                    $pago->monto = $dataPago['monto'];
                    $pago->venta_id = $venta->id;
                    $pago->created_at = $now;
                    $pago->updated_at = NULL;
                    $pago->save();
                }
            }

            //lista de detalles
            foreach ($listDetalle as $ep => $det){
                $detalle = new Detalle_venta();
                $detalle->detalle_producto_id = $det['detalle_producto_id']; 
                $detalle->venta_id = $venta->id;
                $detalle->nombre_producto = $det['nombre_producto'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $dataVenta['tipo_precio']=='1'?$det['precio_menor']:$det['precio_mayor'];
                $detalle->subtotal = $det['subtotal'];
                $detalle->save();
            }

            //Sección notificaciones
            $fechaActual = date('Y-m-d');
            $listaCentros = DB::table('centro')->get();
            foreach ($listaCentros as $centro) {
                $cant = Venta::whereDate('created_at', $fechaActual)->where('centro_id', $centro->id)->count();
                $arregloDatos['c'.$centro->id] = [
                    'nombre' => $centro->nombre,
                    'numero' => $cant,
                ];
            }
            
            $usersAdmin = Usuario::where('rol', 'M')->get();

            foreach($usersAdmin as $notificar){
                Usuario::findOrFail($notificar->id)->notify(new NotifyAdmin($arregloDatos));
            }

            DB::commit();
            $state = 'success';
        } catch (Exception $e) {
            DB::rollback();
            $state = 'exception';
            $exception = $e;
        }

        return [
            'state' => $state,
            'exception' => $exception,
            'arreglo de datos' => $arregloDatos,
        ];
    }

    public function editar(Request $request) {
        if ( !$request->ajax() ) return redirect('/');
        
        $now = Carbon::now('America/Lima')->toDateTimeString();
        $dataCliente = $request->dataCliente;
        $dataVenta = $request->dataVenta;
        $dataPago = $request->dataPago;
        $dataValeU = $request->dataValeU;
        $dataValeG = $request->dataValeG;
        $listDetalle = $request->listDetalle;

        $state = 'error';
        $vale = NULL;
        $exception = NULL;
        $step = 'before transaction';
        try {
            DB::beginTransaction();
            
            //cliente
            $step = 'cliente';
            if ( $dataCliente['id'] != null ) {
                if ( $dataCliente['id'] > 0 ) { //existe
                    $persona = Persona::findOrFail($dataCliente['id']);
                    $persona->cliente = 1;
                    $persona->save();
                } else { //excepciones
                    $dataCliente['id'] = NULL;
                }
            } else if ( $dataCliente['documento'] != NULL ) { //nuevo
                $persona = new Persona();
                $persona->cliente = 1;
                if ( strlen($dataCliente['documento']) == 8 ){
                    $persona->dni = $dataCliente['documento'];
                    $persona->nombres = mb_convert_case($dataCliente['nombres'], MB_CASE_TITLE, "UTF-8");
                    $persona->apellidos = mb_convert_case($dataCliente['apellidos'], MB_CASE_TITLE, "UTF-8");
                    $persona->tipo = 'P';
                } else {
                    $persona->ruc = $dataCliente['documento'];
                    $persona->razon_social = $dataCliente['razon_social'];
                    $persona->tipo = 'E';
                }
                $persona->save();
                $dataCliente['id'] = $persona->id;
            }

            //venta
            $step = 'vemta';
            $venta = Venta::findOrFail($dataVenta['id']);
            $venta->tipo = $dataVenta['tipo_pago'].$dataVenta['tipo_precio'];
            $venta->total_venta = $dataVenta['total_venta'];
            $venta->total_descuento = $dataVenta['total_descuento'];
            $venta->total = $dataVenta['total'];
            $venta->total_faltante = $dataVenta['total_faltante'];
            $venta->cliente_id = $dataCliente['id'];
            $venta->updated_at = $now;
            $venta->save();

            //vale usado
            $step = 'vale usado';
            if ( $dataValeU['id'] != NULL ){
                if ( $dataValeU['venta_usada_id'] == NULL ) {
                    $vale = Vale::findOrFail($dataValeU['id']);
                    $vale->venta_usada_id = $venta->id;
                    $vale->updated_at = $now;
                    $vale->save();
                }
            }

            // vale generado
            $step = 'vale generado';
            if ( $dataValeG['monto'] != NULL ){
                if ( $dataValeG['monto'] > 0 ) {
                    $vale = new Vale();
                    $vale->persona_id = $persona->id;
                    $vale->venta_generada_id = $venta->id;
                    $vale->monto = $dataValeG['monto'];
                    $vale->created_at = $now;
                    $vale->updated_at = NULL;
                    $vale->save();
                }
            }

            //lista de detalles
            $step = 'detalles de venta';
            foreach($listDetalle as $ep => $det){
                if ( $det['id'] <= 0 ) $detalle = new Detalle_venta();
                else $detalle = Detalle_venta::findOrFail($det['id']);
                
                $detalle->detalle_producto_id = $det['detalle_producto_id']; //AQUI ESTA EL PROBLEMA
                $detalle->venta_id = $venta->id;
                $detalle->nombre_producto = $det['nombre_producto'];
                $detalle->cantidad = $det['cantidad']+$det['cantidad_adicional'];
                $detalle->fallidos = $det['fallidos'];
                $detalle->precio = $dataVenta['tipo_precio']==1?$det['precio_menor']:$det['precio_mayor'];
                $detalle->subtotal = $det['subtotal'];
                $detalle->save();
            }

            DB::commit();
            $step = 'later transaction';
            $state = 'success';
        } catch (Exception $e) {
            DB::rollback();
            $state = 'exception';
            $exception = $e;
        }

        return [
            'vale' => $vale,
            'state' => $state,
            'exception' => $exception,
            'step' => $step
        ];
    }
    
    public function getVentaWithDetalle(Request $request){
        $venta = Venta::findOrFail($request->venta_id);
        $detalle = $venta->getDetalleVenta;

        return [
            'venta' => $venta,
            'detalle' => $detalle
        ];
    }
}
