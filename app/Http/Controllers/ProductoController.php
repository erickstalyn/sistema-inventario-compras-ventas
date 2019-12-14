<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Producto;
use App\SuperProducto;
use App\Data;
use App\Material;
use Exception;

class ProductoController extends Controller {
    
    public function listar(Request $request){
        // if ( !$request->ajax() ) return redirect('/') ;
        
        $estado = $request->estado;
        $texto = $request->texto;
        $filas = $request->filas;

        $productos = Producto::where(function ($query) use ($texto) {
                                if ( $texto != '' ) {
                                    $query->where('nombre', 'like', $texto.'%')
                                        ->orWhere('codigo', 'like', '%'.$texto.'%');
                                }
                            })
                            ->orderBy('id', 'desc')->paginate($filas);

        return [
            'paginacion' => [
                'total' => $productos->total(),
                'currentPage' => $productos->currentPage(),
                'perPage' => $productos->perPage(),
                'lastPage' => $productos->lastPage(),
                'firstItem' => $productos->firstItem(),
                'lastItem' => $productos->lastItem()
            ],
            'productos' => $productos
        ];
    }

    public function agregar(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        try {
            DB::beginTransaction();
            
            $producto = new Producto();
            $producto->superproducto_id = $request->superproducto_id;
            $producto->nombre = $request->nombre;
            $producto->codigo = $request->codigo;
            $producto->size = $request->size;
            $producto->color = $request->color;
            $producto->precio_menor = $request->precio_menor;
            $producto->precio_mayor = $request->precio_mayor;
            $producto->created_at = Carbon::now('America/Lima')->toDateTimeString();
            $producto->save();

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

            $producto = Producto::findOrFail($request->id);
            $producto->superproducto_id = $request->superproducto_id;
            $producto->nombre = $request->nombre;
            $producto->codigo = $request->codigo;
            $producto->size = $request->size;
            $producto->color = $request->color;
            $producto->precio_menor = $request->precio_menor;
            $producto->precio_mayor = $request->precio_mayor;
            $producto->save();

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

    public function listaProducto(Request $request){
        $productos = Producto::select('size', 'color', 'costo_produccion', 'precio_menor', 'precio_mayor', 'stock')
                            ->where('superproducto_id', '=', $request->id)
                            ->orderBy('id', 'desc')->get();
        
        return $productos;
    }
    
    public function getProductoFiltrado(Request $request){
        if ( !$request->ajax() ) return redirect('/');
        $texto = $request->texto;

        $productos = Producto::select('id','nombre', 'stock', 'costo_produccion', 'codigo')
                            ->where(function ($query) use ($texto) {
                                if ( $texto != '' ) {
                                    $query->where('nombre', 'like', $texto . '%')
                                        ->orWhere('codigo', '=', $texto);
                                }
                            })
                            ->orderBy('nombre', 'asc')->get();
        return [
            'productos' => $productos
        ];
    }

}
