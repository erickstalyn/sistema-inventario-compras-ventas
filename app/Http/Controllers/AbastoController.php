<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;
use App\Abasto;
use App\Persona;
use App\Pago;
use App\Envio;
use App\Detalle_abasto;

class AbastoController extends Controller
{
    public function listar(Request $request){
        if ( !$request->ajax() ) return redirect('/') ;
        
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

        $abastos = Abasto::select('abasto.id as id', DB::raw("concat_ws(' ', persona.nombres, persona.apellidos) as proveedor_persona"),
                        'persona.razon_social as proveedor_empresa', 'persona.dni as dni', 'persona.ruc as ruc',
                        'centro_to_id', 'centro.nombre as nombre_centro', 'abasto.created_at as fecha_envio',
                        'abasto.total as total', 'abasto.total_faltante as total_faltante',
                        'abasto.tipo as tipo_abasto', 'envio.estado as estado_envio')
                        ->join('persona', 'abasto.proveedor_id', '=', 'persona.id')
                        ->leftjoin('envio', 'abasto.id', '=', 'envio.abasto_id')
                        ->leftjoin('centro', 'envio.centro_to_id', 'centro.id')
                            ->where(function ($query) use ($texto) {
                                if ( $texto != '' ) {
                                    $query->where('persona.razon_social', 'like', '%'.$texto.'%')
                                        ->orWhere('persona.nombres', 'like', '%'. $texto . '%')
                                        ->orWhere('persona.apellidos', 'like', '%'. $texto . '%');
                                }
                            })
                            ->where(function ($query) use ($estado) {
                                if ( $estado != 3 ) {
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
                            ->where('abasto.centro_id', '=', null)
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

            $proveedor = $request->proveedor;
            $now = Carbon::now('America/Lima')->toDateString();
            //Agrego la produccion
            $abasto = new Abasto();//AQUI ME QUEDE
            $abasto->total = $request->total;
            $abasto->tipo = $request->tipo;
            $abasto->total_faltante = $request->total;

            $abasto->created_at = $now;
            //Verifico si existe el proveedor
            if($proveedor['id'] == 0){ //No existe el proveedor
                //Insertamos al proveedor
                $persona = new Persona();
                $persona->proveedor = 1;
                if(strlen($proveedor['documento']) == 8){
                    //Convertimos los nombres y apellidos
                    $newNombres = mb_convert_case($proveedor['nombres'], MB_CASE_TITLE, "UTF-8");
                    $newApellidos = mb_convert_case($proveedor['apellidos'], MB_CASE_TITLE, "UTF-8");
                    $persona->dni = $proveedor['documento'];
                    $persona->nombres = $newNombres;
                    $persona->apellidos = $newApellidos;
                    $persona->tipo = 'P';
                    // $abasto->proveedor_nombre = $newNombres . ' ' . $newApellidos;
                }else{
                    $persona->ruc = $proveedor['documento'];
                    $persona->razon_social = $proveedor['razon_social'];
                    $persona->tipo = 'E';
                    // $abasto->proveedor_nombre = $proveedor['razon_social'];
                }
                $persona->save();
                $abasto->proveedor_id = $persona->id;
            }else{ //Ya existe el proveedor
                $persona = Persona::findOrFail($proveedor['id']);
                $persona->proveedor = 1;
                $persona->save();

                $abasto->proveedor_id = $proveedor['id'];

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
        return $pdf->download('lista_abasto_silmar_' . $request->code . '.pdf');
            
    }
}
