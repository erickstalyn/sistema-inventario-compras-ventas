<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ProductoMaterial;
use Exception;

class ProductoMaterialController extends Controller
{
    public function agregar(Request $request) {
        try {
            DB::beginTransaction();
            
            for ($i = 0; $i < count($request->listaproductomaterial); $i++) {
                if ( $request->$listaproductomaterial[$i]['id']->id == 0 ) {
                    $productomaterial = new ProductoMaterial();
                } else {
                    $productomaterial = ProductoMaterial::findOrFail($request->$listaproductomaterial[$i]['id']);
                }
                
                $productomaterial->producto_id = $request->$id;
                $productomaterial->material_id = $request->$listaproductomaterial[$i]['material_id'];
                $productomaterial->nombre = $request->$listaproductomaterial[$i]['nombre'];
                $productomaterial->unidad = $request->$listaproductomaterial[$i]['unidad'];
                $productomaterial->costo_unitario = $request->$listaproductomaterial[$i]['costo_unitario'];
                $productomaterial->cantidad = $request->$listaproductomaterial[$i]['cantidad'];
                $productomaterial->subtotal = $request->$listaproductomaterial[$i]['subtotal'];
                $productomaterial->save();
            }

            DB::commit();
            $error = NULL;
        } catch (Exception $e) {
            DB::rollback();
            $error = $e;
        }

        return [
            'estado' => $error==NULL?0:1,
            'error' => $error
        ];
    }

    public function listaProductoMaterial(Request $request){
        $lista = ProductoMaterial::select('id', 'material_id', 'nombre', 'unidad', 'costo_unitario', 'cantidad', 'subtotal')
                                ->where('producto_id', '=', $request->producto_id)
                                ->orderBy('id', 'desc')->get();
        return $lista;
    }
}
