<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Categoria;
use App\Producto;
use Exception;

class CategoriaController extends Controller{

    public function listar(Request $request){
        if ( !$request->ajax() ) return redirect('/') ;
        
        $texto = $request->texto;
        $filas = $request->filas;

        $categorias = Categoria::select('id', 'nombre', 'descripcion', 'stock', 'created_at')
                            ->where(function ($query) use ($texto) {
                                if ( $texto != '' ) {
                                    $query->where('nombre', 'like', '%'.$texto.'%');
                                }
                            })
                            ->orderBy('id', 'desc')->paginate($filas);

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

            $now = Carbon::now('America/Lima')->toDateString();

            $categoria = new Categoria();
            // $categoria->nombre = ucfirst($request->nombre);
            $categoria->nombre = mb_strtoupper($request->nombre, 'UTF-8');
            $categoria->descripcion = $request->descripcion==''?NULL:$request->descripcion;
            $categoria->created_at = $now;
            $categoria->save();

            foreach ($request->listaproducto as $p) {
                $producto = new Producto();
                $producto->categoria_id = $categoria->id;
                $producto->nombre = mb_strtoupper($p['nombre'], 'UTF-8');
                $producto->precio_menor = $p['precio_menor'];
                $producto->precio_mayor = $p['precio_mayor'];
                $producto->created_at = $now;
                $producto->save();
            }

            DB::commit();
            $error = NULL;
        } catch(Exception $e) {
            DB::rollback();
            $error = (string) $e;
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

            $categoria = Categoria::findOrFail($request->id);
            // $categoria->nombre = ucfirst($request->nombre);
            $categoria->nombre = mb_strtoupper($request->nombre, 'UTF-8');
            $categoria->descripcion = $request->descripcion==''?NULL:$request->descripcion;
            $categoria->save();
            
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
    
    public function selectCategoria(Request $request){
        $categorias = Categoria::select('id', 'nombre')
                            ->orderBy('nombre', 'desc')->get();
        return $categorias;
    }

    public function generatePdf(){
        $categoria = Categoria::select('id', 'nombre', 'descripcion', 'stock', 'created_at')
                        ->orderBy('id', 'asc')->get();

        $cont = Categoria::count();
        
        $pdf = \PDF::loadView('pdf.categoriapdf', ['categoria'=>$categoria, 'cont'=>$cont]);
        return $pdf->download('lista_categoria.pdf');
    }

}

