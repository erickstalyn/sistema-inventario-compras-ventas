<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Marca;
use DB;
use Exception;

class MarcaController extends Controller
{
    public function listar(Request $request) {
        if(!$request->ajax()) return redirect('/');
    
        $texto = $request->texto;
        $filas = $request->filas;
    
        $marcas = Marca::where(function ($query) use ($texto) {
                    if($texto != '') $query->where('nombre', 'like', '%' . $texto . '%');
                    })
                    ->orderBy('id', 'desc')->paginate($filas);
        return [
          'paginacion' => [
            'total' => $marcas->total(),
            'currentPage' => $marcas->currentPage(),
            'perPage' => $marcas->perPage(),
            'lastPage' => $marcas->lastPage(),
            'firstItem' => $marcas->firstItem(),
            'lastItem' => $marcas->lastItem()
          ],
          'marcas' => $marcas
        ];
    }
    
    public function agregar(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        try {
            DB::beginTransaction();

            $marca = new Marca();
            $marca->nombre = $request->nombre;
            $marca->save();

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

            $marca = Marca::findOrFail($request->id);
            $marca->nombre = $request->nombre;
            $marca->save();

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
