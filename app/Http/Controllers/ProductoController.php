<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Producto;
use App\SubProducto;
use App\Categoria;
use App\Marca;
use App\TipoCaracteristica;
use App\Caracteristica;
use Exception;

class ProductoController extends Controller{

    public function listar(Request $request){
        if ( !$request->ajax() ) return redirect('/') ;
        
        $texto = $request->texto;
        $filas = $request->filas;

        $productos = Producto::leftJoin('categoria', 'categoria.id', '=', 'producto.categoria_id')
                            ->leftJoin('marca', 'marca.id', '=', 'producto.marca_id')
                            ->select('producto.id', 'categoria.nombre AS categoria', 'marca.nombre AS marca', 'modelo', 'producto.nombre', 'descripcion', 'stock', 'created_at')
                            ->where('producto.estado', '=', 1)
                            ->where(function ($query) use ($texto) {
                                if ( $texto != '' ) {
                                    $query->where('categoria.nombre', 'like', '%'.$texto.'%')
                                        ->orWhere('marca.nombre', 'like', '%'.$texto.'%')
                                        ->orWhere('modelo', 'like', '%'.$texto.'%')
                                        ->orWhere('producto.nombre', 'like', '%'.$texto.'%');
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

        $dataProducto = $request->producto;
        $dataSubproductos = $request->Subproductos;
        
        try {
            DB::beginTransaction();

            $now = Carbon::now('America/Lima')->toDateTimeString();

            $producto = new Producto();
            $producto->categoria_id = $dataProducto['categoria_id'];
            $producto->marca_id = $dataProducto['marca_id']!=0?$dataProducto['marca_id']:NULL;
            $producto->modelo = $dataProducto['modelo'];

            if ( trim($dataProducto['nombre']) == '' ) {
                $categoria = Categoria::select('nombre')->where('id', '=', $dataProducto['categoria_id'])->first()['nombre'];
                $marca = $dataProducto['marca_id']!=0?' '.Marca::select('nombre')->where('id', '=', $dataProducto['marca_id'])->first()['nombre']:'';
                $modelo = ' ' . $producto->modelo;
                $producto->nombre = strtr($categoria . $marca . $modelo,"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
            } else {
                $producto->nombre = $dataProducto['nombre'];
            }

            $producto->descripcion = $request->descripcion!=''?$request->descripcion:NULL;
            $producto->created_at = $now;
            $producto->save();

            $index = -1;
            foreach ($request->subproductos as $dataSubproducto) {
                $index++;

                $subproducto = new Subproducto();
                $subproducto->producto_id = $producto->id;
                $subproducto->nombre = $producto->nombre;
                $subproducto->code_unique = "";
                $subproducto->caracteristicas = "";
                $caracteristicas_json = [];

                foreach ($dataSubproducto['caracteristicas'] as $caracteristica) {
                    if ( $caracteristica['nombre'] != '' ) {
                        $subproducto->nombre .= ' ' . $caracteristica['nombre'];

                        $subproducto->code_unique .= strval(base_convert(TipoCaracteristica::where('nombre', '=', $caracteristica['tipo_caracteristica'])->first()['id'], 10, 36));
                        $subproducto->code_unique .= str_pad(base_convert(Caracteristica::where('nombre', '=', $caracteristica['nombre'])->first()['id'], 10, 36), 2, '0', STR_PAD_LEFT);
                        
                        $subproducto->caracteristicas .= ($subproducto->caracteristicas==''?'':' ').$caracteristica['nombre'];
                        $caracteristicas_json[] = [
                            'tipo_caracteristica' => $caracteristica['tipo_caracteristica'],
                            'nombre' => $caracteristica['nombre']
                        ];
                    }
                }
                unset($caracteristica);

                $subproducto->caracteristicas_json = $caracteristicas_json;
                $subproducto->codigo = implode("", array_merge(explode('-', explode(' ', $now)[0]), explode(':', explode(' ', $now)[1]))) . $index;
                $subproducto->precio_menor = $dataSubproducto['precio_menor'];
                $subproducto->precio_mayor = $dataSubproducto['precio_mayor'];
                $subproducto->created_at = $now;
                $subproducto->save();
            }
            unset($dataSubproducto);

            DB::commit();

            return [
                'success' => true
            ];
        } catch(Exception $e) {
            DB::rollback();
            
            return [
                'success' => false,
                'status' => 'exception',
                'exception' => $e,
                'step' => $step,
            ];
        }
    }

    public function editar(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        try {
            DB::beginTransaction();

            $superproducto = SuperProducto::findOrFail($request->id);
            // $superproducto->nombre = ucfirst($request->nombre);
            $superproducto->nombre = strtr(strtoupper($request->nombre),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
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

    public function generatePdf(){
        $superproducto = SuperProducto::select('id', 'nombre', 'descripcion', 'superstock', 'created_at')
                        ->orderBy('id', 'asc')->get();

        $cont = SuperProducto::count();
        
        $pdf = \PDF::loadView('pdf.superproductopdf', ['superproducto'=>$superproducto, 'cont'=>$cont]);
        return $pdf->download('lista_superproductos_silmar.pdf');
    }

    public function getCategorias(Request $request){
        if ( !$request->ajax() ) return redirect('/');
        
        return Categoria::all();
    }
    
    public function getMarcas(Request $request){
        if ( !$request->ajax() ) return redirect('/');

        return Marca::all();
    }

}

