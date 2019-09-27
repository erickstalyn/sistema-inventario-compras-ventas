<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Producto;
use App\Categoria;
use Exception;

class ProductoController extends Controller {
    
    public function listar(Request $request){
        if ( !$request->ajax() ) return redirect('/') ;
        
        $estado = $request->estado;
        $texto = $request->texto;
        $filas = $request->filas;
        $ordenarPor = $request->ordenarPor;
        $orden = $request->orden;

        $productos = Producto::join('categoria', 'producto.categoria_id', '=', 'categoria.id')
                            ->select('producto.id', 'producto.categoria_id', 'producto.codigo', 'producto.nombre', 'producto.precio', 'producto.stock', 'producto.descripcion', 'producto.estado',
                                // DB::raw('Date_Format(persona.created_at,\'' .'%d-%M-%Y' . '\') as fecha_creacion'), // para mysql
                                // DB::raw('to_char(persona.created_at,\'' .'DD-Mon-YYYY' . '\') as fecha_creacion'), //para postgresql
                                'producto.created_at', 'producto.updated_at', 'producto.deleted_at', 'categoria.nombre as categoria_nombre')
                            ->where(function ($query) use ($estado) {
                                if ( $estado != 2 ) {
                                    $query->where('producto.estado', '=', $estado);
                                }
                            })
                            ->where(function ($query) use ($texto) {
                                if ( $texto != '' ) {
                                    $query->where('producto.nombre', 'like', '%'.$texto.'%')
                                        ->orWhere('producto.codigo', 'like', '%'.$texto.'%')
                                        ->orWhere('producto.precio', 'like', '%'.$texto.'%')
                                        ->orWhere('producto.stock', 'like', '%'.$texto.'%')
                                        ->orWhere('producto.descripcion', 'like', '%'.$texto.'%');
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

            $producto = new Producto();
            $producto->nombre = $request->nombre;
            $producto->categoria_id = $request->categoria_id;
            $producto->codigo = $request->codigo==''?NULL:$request->codigo;
            $producto->precio = $request->precio;
            $producto->descripcion = $request->descripcion==''?NULL:$request->descripcion;
            $producto->created_at = Carbon::now('America/Lima')->toDateTimeString();
            $producto->updated_at = NULL;
            $producto->save();

            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
        }
    }

    public function editar(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        try {
            DB::beginTransaction();

            $producto = Producto::findOrFail($request->id);
            $producto->nombre = $request->nombre;
            $producto->categoria_id = $request->categoria_id;
            $producto->codigo = $request->codigo==''?NULL:$request->codigo;
            $producto->precio = $request->precio;
            $producto->descripcion = $request->descripcion==''?NULL:$request->descripcion;
            $producto->updated_at = Carbon::now('America/Lima')->toDateTimeString();
            $producto->save();

            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
        }
    }

    public function activar(Request $request){
        try {
            DB::beginTransaction();

            $producto = Producto::findOrFail($request->id);
            $producto->estado = 1;
            $producto->updated_at = Carbon::now('America/Lima')->toDateTimeString();
            $producto->deleted_at = NULL;
            $producto->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return $e;
        }
    }

    public function desactivar(Request $request){
        try {
            DB::beginTransaction();

            $producto = Producto::findOrFail($request->id);
            $producto->estado = 0;
            $producto->updated_at = Carbon::now('America/Lima')->toDateTimeString();
            $producto->deleted_at = Carbon::now('America/Lima')->toDateTimeString();
            $producto->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }
    }

    public function selectCategoria(Request $request){
        $categorias = Categoria::select('id', 'nombre')
                            ->where('estado', '=', 1)
                            ->orderBy('nombre', 'desc')->get();

        return $categorias;
    }

}
