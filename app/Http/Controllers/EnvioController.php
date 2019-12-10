<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Envio;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class EnvioController extends Controller
{
    public function listar(Request $request){
        // if ( !$request->ajax() ) return redirect('/') ;
        
        $estado = $request->estado;
        $texto = $request->texto;
        $filas = $request->filas;
        $ordenarPor = $request->ordenarPor;
        $orden = $request->orden;
        $hoy = Carbon::now('America/Lima')->toDateString();
        $idCentro = $request->idCentro;
        //Fechas
        $dia = $request->dia;
        $mes = $request->mes;
        $year = $request->year;

        $envios = Envio::select('envio.id as id', 'envio.estado', 'envio.abasto_id', 'envio.created_at as fecha_envio',
                        'updated_at as fecha_cambio', 'centro.nombre as centro_origen')
                        ->leftjoin('centro', 'centro.id', '=', 'envio.centro_from_id')
                        ->where(function ($query) use ($texto) {
                            if ( $texto != '' ) {
                                if(strpos('administracion', $texto) === false){
                                    $query->where('centro.nombre', 'like', '%'.$texto.'%');
                                }else{
                                    $query->where('envio.centro_from_id', '=', null);
                                }
                            }
                        })
                        ->where(function ($query) use ($estado) {
                            if ( $estado != 3 ) {
                                $query->where('envio.estado', '=', $estado);
                            }
                        })
                        ->where('envio.centro_to_id', '=', $idCentro)
                        // ->orderBy('envio.id', 'asc')->get();
                        ->orderBy($ordenarPor, $orden)->paginate($filas);

        return [
            'paginacion' => [
                'total' => $envios->total(),
                'currentPage' => $envios->currentPage(),
                'perPage' => $envios->perPage(),
                'lastPage' => $envios->lastPage(),
                'firstItem' => $envios->firstItem(),
                'lastItem' => $envios->lastItem()
            ],
            'envios' => $envios
        ];
    }

    public function setEstado(Request $request){
        try {
            DB::beginTransaction();
            $envio = Envio::findOrFail($request->id);
            $envio->updated_at = Carbon::now('America/Lima')->toDateString();
            $envio->estado = $request->estado;
            $envio->save();

            DB::commit();
        } catch (Exception $e) {
            echo($e);
            DB::rollback();
        }
    }
}
