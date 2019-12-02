<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Categoria;
use Exception;

class CategoriaController extends Controller
{

    public function listar(Request $request){
        if ( !$request->ajax() ) return redirect('/') ;
        
        $estado = $request->estado;
        $texto = $request->texto;
        $filas = $request->filas;
        $ordenarPor = $request->ordenarPor;
        $orden = $request->orden;

        $categorias = Categoria::leftJoin('producto', 'categoria.id', '=', 'producto.categoria_id')
                            ->select('categoria.id', 'categoria.categoria_id', 'categoria.nombre', 'categoria.descripcion', 'categoria.estado', DB::raw('count(producto.id) as cant_productos'),
                                // DB::raw('Date_Format(persona.created_at,\'' .'%d-%M-%Y' . '\') as fecha_creacion'), // para mysql
                                // DB::raw('to_char(persona.created_at,\'' .'DD-Mon-YYYY' . '\') as fecha_creacion'), //para postgresql
                                'categoria.created_at', 'categoria.updated_at', 'categoria.deleted_at')
                            ->where(function ($query) use ($estado) {
                                if ( $estado != 2 ) {
                                    $query->where('categoria.estado', '=', $estado);
                                }
                            })
                            ->where(function ($query) use ($texto) {
                                if ( $texto != '' ) {
                                    $query->where('categoria.nombre', 'like', '%'.$texto.'%')
                                        ->orWhere('categoria.descripcion', 'like', '%'.$texto.'%');
                                }
                            })
                            ->orderBy($ordenarPor, $orden)->paginate($filas);

        return [
            'paginacion' => [
                'total' => $categorias->total(),
                'currentPage' => $categorias->currentPage(),
                'perPage' => $categorias->perPage(),
                'lastPage' => $categorias->lastPage(),
                'firstItem' => $categorias->firstItem(),
                'lastItem' => $categorias->lastItem()
            ],
            'categorias' => $categorias
        ];
    }

    public function agregar(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        try {
            DB::beginTransaction();

            $categoria = new Categoria();
            $categoria->nombre = $request->nombre;
            $categoria->categoria_id = $request->categoria_id==''?NULL:$request->categoria_id;
            $categoria->descripcion = $request->descripcion==''?NULL:$request->descripcion;
            $categoria->created_at = Carbon::now('America/Lima')->toDateTimeString();
            $categoria->updated_at = NULL;
            $categoria->save();

            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
        }
    }

    public function editar(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        try {
            DB::beginTransaction();

            $categoria = Categoria::findOrFail($request->id);
            $categoria->nombre = $request->nombre;
            $categoria->categoria_id = $request->categoria_id==''?NULL:$request->categoria_id;
            $categoria->descripcion = $request->descripcion==''?NULL:$request->descripcion;
            $categoria->updated_at = Carbon::now('America/Lima')->toDateTimeString();
            $categoria->save();
            
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
        }
    }

    public function activar(Request $request){
        try {
            DB::beginTransaction();

            $categoria = Categoria::findOrFail($request->id);
            $categoria->estado = 1;
            $categoria->updated_at = Carbon::now('America/Lima')->toDateTimeString();
            $categoria->deleted_at = NULL;
            $categoria->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return $e;
        }
    }

    public function desactivar(Request $request){
        try {
            DB::beginTransaction();

            $categoria = Categoria::findOrFail($request->id);
            $categoria->estado = 0;
            $categoria->updated_at = Carbon::now('America/Lima')->toDateTimeString();
            $categoria->deleted_at = Carbon::now('America/Lima')->toDateTimeString();
            $categoria->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }
    }

}

