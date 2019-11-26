<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\SuperProducto;
use Exception;

class SuperProductoController extends Controller{

    public function listar(Request $request){
        if ( !$request->ajax() ) return redirect('/') ;
        
        $estado = $request->estado;
        $texto = $request->texto;
        $filas = $request->filas;
        $ordenarPor = $request->ordenarPor;
        $orden = $request->orden;

        $superproductos = SuperProducto::select('id', 'nombre', 'descripcion', 'estado', 'superstock', 'created_at')
                            ->where(function ($query) use ($estado) {
                                if ( $estado != 2 ) {
                                    $query->where('estado', '=', $estado);
                                }
                            })
                            ->where(function ($query) use ($texto) {
                                if ( $texto != '' ) {
                                    $query->where('nombre', 'like', '%'.$texto.'%')
                                        ->orWhere('descripcion', 'like', '%'.$texto.'%');
                                }
                            })
                            ->orderBy($ordenarPor, $orden)->paginate($filas);

        return [
            'paginacion' => [
                'total' => $superproductos->total(),
                'currentPage' => $superproductos->currentPage(),
                'perPage' => $superproductos->perPage(),
                'lastPage' => $superproductos->lastPage(),
                'firstItem' => $superproductos->firstItem(),
                'lastItem' => $superproductos->lastItem()
            ],
            'superproductos' => $superproductos
        ];
    }

    public function agregar(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        try {
            DB::beginTransaction();

            $superproducto = new SuperProducto();
            $superproducto->nombre = $request->nombre;
            $superproducto->descripcion = $request->descripcion==''?NULL:$request->descripcion;
            $superproducto->created_at = Carbon::now('America/Lima')->toDateTimeString();
            $superproducto->save();

            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            $error = $e;
        }
        
        return [
            'estado' => $error==NULL?1:0,
            'error' => $error
        ];
    }

    public function editar(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        try {
            DB::beginTransaction();

            $superproducto = SuperProducto::findOrFail($request->id);
            $superproducto->nombre = $request->nombre;
            $superproducto->descripcion = $request->descripcion==''?NULL:$request->descripcion;
            $superproducto->save();
            
            DB::commit();

        } catch(Exception $e) {
            DB::rollback();
            $error = $e;
        }

        return [
            'estado' => $error==NULL?1:0,
            'error' => $error
        ];
    }

    public function setEstado(Request $request){
        try {
            DB::beginTransaction();

            $superproducto = SuperProducto::findOrFail($request->id);
            $superproducto->estado = $request->estado;
            $superproducto->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            $error = $e;
        }

        return [
            'estado' => $error==NULL?1:0,
            'error' => $error
        ];
    }

}

