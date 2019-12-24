<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\SuperProducto;
use App\Producto;
use Exception;

class SuperProductoController extends Controller{

    public function listar(Request $request){
        if ( !$request->ajax() ) return redirect('/') ;
        
        $texto = $request->texto;
        $filas = $request->filas;

        $superproductos = SuperProducto::select('id', 'nombre', 'descripcion', 'superstock', 'created_at')
                            ->where(function ($query) use ($texto) {
                                if ( $texto != '' ) {
                                    $query->where('nombre', 'like', '%'.$texto.'%');
                                }
                            })
                            ->orderBy('id', 'desc')->paginate($filas);

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

            $now = Carbon::now('America/Lima')->toDateString();

            $superproducto = new SuperProducto();
            $superproducto->nombre = ucfirst($request->nombre);
            $superproducto->descripcion = $request->descripcion==''?NULL:$request->descripcion;
            $superproducto->created_at = $now;
            $superproducto->save();

            for ($i = 0; $i < count($request->listaproducto); $i++) {
                $producto = new Producto();
                $producto->superproducto_id = $superproducto->id;
                $producto->nombre = $superproducto->nombre.' '.$request->listaproducto[$i]['size'].' '.$request->listaproducto[$i]['color'];
                $producto->size = $request->listaproducto[$i]['size'];
                $producto->color = $request->listaproducto[$i]['color'];
                $producto->precio_menor = $request->listaproducto[$i]['precio_menor'];
                $producto->precio_mayor = $request->listaproducto[$i]['precio_mayor'];
                $producto->created_at = $now;
                $producto->save();
            }

            DB::commit();
            $error = NULL;
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
            $error = NULL;
        } catch(Exception $e) {
            DB::rollback();
            $error = $e;
        }

        return [
            'estado' => $error==NULL?1:0,
            'error' => $error
        ];
    }
    
    public function selectSuperProducto(Request $request){
        $superproductos = SuperProducto::select('id', 'nombre')
                                    ->orderBy('nombre', 'desc')->get();
        return $superproductos;
    }

}

