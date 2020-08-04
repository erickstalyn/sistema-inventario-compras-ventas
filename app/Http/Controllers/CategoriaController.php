<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Categoria;
use Exception;

class CategoriaController extends Controller
{
  public function listar(Request $request) {
    if(!$request->ajax()) return redirect('/');

    $texto = $request->texto;
    $filas = $request->filas;

    $categorias = Categoria::where(function ($query) use ($texto) {
                    if($texto != '') $query->where('nombre', 'like', '%' . $texto . '%');
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

      $categoria = new Categoria();
      $categoria->nombre = $request->nombre;
      $categoria->save();

      DB::commit();
      return [
        'success' => true
      ];
    } catch(Exception $e) {
      DB::rollback();
      return [
        'success' => false,
        'exception' => $e
      ];
    }
  }

  public function editar(Request $request){
    if ( !$request->ajax() ) return redirect('/');
    try {
      DB::beginTransaction();

      $categoria = Categoria::findOrFail($request->id);
      $categoria->nombre = $request->nombre;
      $categoria->save();

      DB::commit();
      return [
        'success' => true
      ];
    } catch(Exception $e) {
      DB::rollback();
      return [
        'success' => false,
        'exception' => $e
      ];
    }
  }
}
