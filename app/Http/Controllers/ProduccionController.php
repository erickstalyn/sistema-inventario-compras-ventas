<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Produccion;
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
        // echo("Soy el now: " . $hoy);

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
                            ->where(function ($query) use ($texto) {
                                if ( $texto != '' ) {
                                    $query->where('nombre', 'like', '%'.$texto.'%');
                                }
                            })
                            ->orderBy($ordenarPor, $orden)->paginate($filas);

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
}
