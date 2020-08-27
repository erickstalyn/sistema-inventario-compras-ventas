<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;
use App\Concepto;
use App\Caja;
use App\Abasto;
use App\Persona;
use App\Pago;
use App\Envio;
use App\Detalle_abasto;
use App\Detalle_funcion;

class AbastoController extends Controller
{
    public function listar(Request $request){
        // if ( !$request->ajax() ) return redirect('/') ;
        
        $estado = $request->estado;
        $texto = $request->texto;
        $filas = $request->filas;
        $hoy = Carbon::now('America/Lima')->toDateString();
        
        //Fechas
        $dia = $request->dia;
        $mes = $request->mes;
        $year = $request->year;

        $abastos = Abasto::select('abasto.proveedor_nombre', 'abasto.tipo', 'abasto.created_at as fecha_envio', 'centro.nombre as centro_to_nombre', 'abasto.total', 'envio.estado as envio_estado')
                    ->join('envio', 'envio.abasto_id', 'abasto.id')
                    ->join('centro', 'centro.id', 'envio.centro_to_id')
                    ->orderBy('abasto.id', 'desc')->paginate($filas);

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

    public function listByCenter(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        $centro_id = $request->centro_id;
        $paid = $request->paid;

        $list = Abasto::select('abasto.id as a_id', 'proveedor_nombre', 'created_at',
                                'da.id as da_id', 'nombre_producto', 'cantidad')
                        ->join('detalle_abasto AS da', 'da.abasto_id', '=', 'abasto.id')
                        ->where('total', '=', 0)
                        ->where('tipo', '=', '1')
                        ->where('centro_id', '=', $centro_id)
                        ->where('administrador_id', '=', NULL)->get();

        return $list;
    }

    public function pay(Request $request){
        $state = 'transaction-validate';
        $message = NULL;
        $step = NULL;

        {   // Validacion: que la consulta sea ajax
            if ( !$request->ajax() ) return redirect('/');
        }
        {   // Validacion: que la caja chica este abierta
            $now = Carbon::now('America/Lima')->toDateString();
            $caja = Caja::where('centro_id', '=', $request->dataCentro['id'])
                        ->where(DB::raw('CAST(start AS DATE)'), '=', $now)->first();
            
            if ( $caja == NULL ) {
                return [
                    'state' => $state,
                    'validate' => 'box-no-exist'
                ];
            }
            if ( $caja->state == 0 ) {
                return [
                    'state' => $state,
                    'validate' => 'box-close'
                ];
            }
        }

        $now = Carbon::now('America/Lima')->toDateTimeString();
        $dataListaAbasto = $request->dataListaAbasto;

        try {
            DB::beginTransaction();
            $state = 'transacion-start';

            foreach ($dataListaAbasto as $compra) {
                if ( $compra['total'] != NULL ) {
                    $abasto = Abasto::findOrFail($compra['id']);
                    $abasto->total = $compra['total'];
                    $abasto->save();

                    $pago = new Pago();
                    $pago->abasto_id = $abasto->id;
                    $pago->monto = $abasto->total;
                    $pago->created_at = $now;
                    $pago->save();

                    $concepto = new Concepto();
                    $concepto->caja_id = $caja->id;
                    $concepto->type = 0;
                    $concepto->descripcion = 'Pago por compra de productos externos a "'.$abasto->proveedor_nombre.'"';
                    $concepto->monto = $pago->monto;
                    $concepto->created_at = $now;
                    $concepto->save();
                    
                    foreach ( $compra['lista_detalle_abasto'] as $det ) {
                        $detalle = Detalle_abasto::findOrFail($det['id']);
                        $detalle->costo_abasto = $det['costo_abasto'];
                        $detalle->subtotal = $det['subtotal'];
                        $detalle->save();
                    }
                }
            }

            DB::commit();
            $state = 'transaction-success';
        } catch (Exception $e) {
            DB::rollback();
            $state = 'transaction-exception';
            $message = $e;
        }

        return [
            'state' => $state,
            'step' => $step,
            'exception' => $message,
            'caja' => $caja
        ];
    }

    public function agregar(Request $request){
        if ( !$request->ajax() ) return redirect('/');
        try {
            DB::beginTransaction();

            $proveedor = $request->proveedor;
            $now = Carbon::now('America/Lima')->toDateString();
            //Agrego la produccion
            $abasto = new Abasto();//AQUI ME QUEDE
            $abasto->total = $request->total;
            $abasto->tipo = $request->tipo;
            $abasto->total_faltante = $request->total;

            $abasto->created_at = $now;
            //Verifico si existe la PERSONA
            if($proveedor['id'] == 0){ //No existe la PERSONA
                //Insertamos al proveedor
                $persona = new Persona();
                // $persona->proveedor = 1;
                if(strlen($proveedor['documento']) == 8){
                    //Convertimos los nombres y apellidos
                    $persona->dni = trim($proveedor['documento']);
                    $persona->nombres = trim(mb_convert_case($proveedor['nombres'], MB_CASE_TITLE, "UTF-8"));
                    $persona->apellidos = trim(mb_convert_case($proveedor['apellidos'], MB_CASE_TITLE, "UTF-8"));
                    $persona->tipo = 'P';
                    // $abasto->proveedor_nombre = $newNombres . ' ' . $newApellidos;
                }else{
                    $persona->ruc = trim($proveedor['documento']);
                    $persona->razon_social = trim($proveedor['razon_social']);
                    $persona->direccion = mb_convert_case($proveedor['direccion'], MB_CASE_TITLE, "UTF-8");
                    $persona->tipo = 'E';
                    // $abasto->proveedor_nombre = $proveedor['razon_social'];
                }
                $persona->save();
                $abasto->proveedor_id = $persona->id;

                //Asignamos su funcion PROVEEDOR
                $detalle_funcion = new Detalle_funcion();
                $detalle_funcion->persona_id = $persona->id;
                $detalle_funcion->funcion_id = 2;
                $detalle_funcion->save();
            }else{ //Ya existe la PERSONA
                $abasto->proveedor_id = $proveedor['id'];
                try {
                    $detalle_funcion = new Detalle_funcion();
                    $detalle_funcion->persona_id = $proveedor['id'];
                    $detalle_funcion->funcion_id = 2;
                    $detalle_funcion->save();
                } catch (Exception $e) {
                }
            }
            $abasto->save();

            if($request->tipo == 1 && $request->pagoInicial > 0){// C y PI -> make a pago
                $pago = new Pago();
                $pago->monto = $request->pagoInicial;
                $pago->abasto_id = $abasto->id;
                $pago->created_at = Carbon::now('America/Lima')->toDateTimeString();
                $pago->save();
            }
            //Registramos el ENVÍO
            $envio = new Envio();
            $envio->tipo = 1;
            $envio->centro_to_id = $request->centro_to_id;
            $envio->abasto_id = $abasto->id;
            $envio->created_at = $now;
            $envio->save();

            //Insertamos los datos del detalle de abasto
            $listaDetalleAbasto = $request->listaDetalleAbasto;

            foreach($listaDetalleAbasto as $ep => $det){
                $detalle = new Detalle_abasto();
                $detalle->nombre_producto = $det['nombre'];
                $detalle->costo_abasto = $det['costo_abasto'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->subtotal = $det['subtotal'];
                $detalle->producto_id = $det['id'];
                $detalle->abasto_id = $abasto->id;
                $detalle->save();
            }

            DB::commit();
        } catch(Exception $e) {
            echo($e);
            DB::rollback();
        }

    }
    
    public function getPagos(Request $request){
        if ( !$request->ajax() ) return redirect('/') ;
        $pagos = Abasto::findOrFail($request->id)->getPagos;
        return $pagos;
    }

    public function getDetalles(Request $request){
        if ( !$request->ajax() ) return redirect('/') ;
        $detalles = Abasto::findOrFail($request->id)->getDetalles;
        return $detalles;
    }

    public function anular(Request $request){
        try {
            DB::beginTransaction();

            $material = Abasto::findOrFail($request->id);
            $material->delete();
            DB::commit();
        } catch (Exception $e) {
            echo($e);
            DB::rollback();
        }
    }

    public function generatePdf(){
        $abasto = Abasto::select('abasto.id as id', DB::raw("concat_ws(' ', persona.nombres, persona.apellidos) as proveedor_persona"),
            'persona.razon_social as proveedor_empresa', 'persona.dni as dni', 'persona.ruc as ruc',
            'centro_to_id', 'centro.nombre as nombre_centro', 'abasto.created_at as fecha_envio',
            'abasto.total as total', 'abasto.total_faltante as total_faltante',
            'abasto.tipo as tipo_abasto', 'envio.estado as estado_envio')
            ->join('persona', 'abasto.proveedor_id', '=', 'persona.id')
            ->leftjoin('envio', 'abasto.id', '=', 'envio.abasto_id')
            ->leftjoin('centro', 'envio.centro_to_id', 'centro.id')
            ->where('abasto.centro_id', '=', null)
            ->orderBy('abasto.id', 'desc')->get();

        $cont = Abasto::count();
        
        $pdf = \PDF::loadView('pdf.abastopdf', ['abasto'=>$abasto, 'cont'=>$cont])->setPaper('a4', 'landscape');
        return $pdf->download('lista_abastos_silmar.pdf');
    }

    public function generatePdfSpecific(Request $request){
        $abasto = Abasto::select('abasto.id as id', DB::raw("concat_ws(' ', persona.nombres, persona.apellidos) as proveedor_persona"),
            'persona.razon_social as proveedor_empresa', 'persona.dni as dni', 'persona.ruc as ruc',
            'centro_to_id', 'centro.nombre as nombre_centro', 'abasto.created_at as fecha_envio',
            'abasto.total as total', 'abasto.total_faltante as total_faltante',
            'abasto.tipo as tipo_abasto', 'envio.estado as estado_envio')
            ->join('persona', 'abasto.proveedor_id', '=', 'persona.id')
            ->leftjoin('envio', 'abasto.id', '=', 'envio.abasto_id')
            ->leftjoin('centro', 'envio.centro_to_id', 'centro.id')
            ->where('abasto.centro_id', '=', null)
            ->where('abasto.id', '=', $request->code)
            ->orderBy('abasto.id', 'desc')->take(1)->get();

        $detalles = Abasto::findOrFail($request->code)->getDetalles;
        $pagos = Abasto::findOrFail($request->code)->getPagos;

        $pdf = \PDF::loadView('pdf.comprobante_abasto', ['abasto'=>$abasto, 'detalles'=>$detalles, 'pagos' => $pagos]);
        return $pdf->download('abasto_silmar_' . $request->code . '.pdf');
            
    }

    public function pruebas(Request $request){
        $persona = Persona::find(2);
        $detalle_funcion = $persona->haveFunction(); 
        return [
            'detalle_funcion' => $detalle_funcion
        ];
    }
}
