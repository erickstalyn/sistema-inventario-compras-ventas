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
        if ( !$request->ajax() ) return redirect('/') ;
        
        $estado = $request->estado;
        $texto = $request->texto;
        $filas = $request->filas;
        $ordenarPor = $request->ordenarPor;
        $orden = $request->orden;

        $productos = Producto::where(function ($query) use ($texto) {
                                if ( $texto != '' ) {
                                    $query->where('nombre', 'like', '%'.$texto.'%')
                                        ->orWhere('descripcion', 'like', '%'.$texto.'%')
                                        ->orWhere('codigo', 'like', '%'.$texto.'%');
                                }
                            })
                            ->orderBy($ordenarPor, $orden)->paginate($filas);

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
            
            $superproducto = SuperProducto::findOrFail($request->superproducto_id);
            
            $producto = new Producto();
            $producto->superproducto_id = $request->superproducto_id;
            $producto->nombre = $superproducto->nombre.' '.$request->size.' '.$request->color;
            $producto->descripcion = $request->descripcion==''?NULL:$request->descripcion;
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

            $superproducto = SuperProducto::findOrFail($request->superproducto_id);

            $producto = Producto::findOrFail($request->id);
            $producto->superproducto_id = $request->superproducto_id;
            $producto->nombre = $superproducto->nombre.' '.$request->size.' '.$request->color;
            $producto->descripcion = $request->descripcion==''?NULL:$request->descripcion;
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

    public function selectSuperProducto(Request $request){
        $superproductos = SuperProducto::select('id', 'nombre')
                                    ->orderBy('nombre', 'desc')->get();

        return $superproductos;
    }
    public function selectSize(Request $request){
        $tamaños = Data::select('nombre')
                    ->where('tipo', '=', 'T')->get();

        return $tamaños;
    }
    public function selectColor(Request $request){
        $colores = Data::select('nombre')
                    ->where('tipo', '=', 'C')->get();

        return $colores;
    }
    public function selectMaterial(Request $request){
        $materiales = Material::orderBy('nombre', 'desc')->get();
        return $materiales;
    }

}
