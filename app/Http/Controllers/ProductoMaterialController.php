<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ProductoMaterial;
use App\Producto;
use Exception;

class ProductoMaterialController extends Controller
{
    public function agregar(Request $request) {
        $lista = $request->listaproductomaterial;

        try {
            DB::beginTransaction();

            for ($i = 0; $i < count($lista); $i++) {
                if ( $lista[$i]['id'] == 0 ) {
                    $productomaterial = new ProductoMaterial();
                    $productomaterial->producto_id = $request->producto_id;
                    $productomaterial->material_id = $lista[$i]['material_id'];
                    $productomaterial->nombre = $lista[$i]['nombre'];
                    $productomaterial->unidad = $lista[$i]['unidad'];
                    $productomaterial->costo_unitario = $lista[$i]['costo_unitario'];
                    $productomaterial->cantidad = $lista[$i]['cantidad'];
                    $productomaterial->subtotal = $lista[$i]['subtotal'];
                    $productomaterial->save();
                } else if ( $lista[$i]['estado'] == 1 ) {
                    $productomaterial = ProductoMaterial::findOrFail($lista[$i]['id']);
                    $productomaterial->producto_id = $request->producto_id;
                    $productomaterial->material_id = $lista[$i]['material_id'];
                    $productomaterial->nombre = $lista[$i]['nombre'];
                    $productomaterial->unidad = $lista[$i]['unidad'];
                    $productomaterial->costo_unitario = $lista[$i]['costo_unitario'];
                    $productomaterial->cantidad = $lista[$i]['cantidad'];
                    $productomaterial->subtotal = $lista[$i]['subtotal'];
                    $productomaterial->save();
                } else {
                    ProductoMaterial::where('id', '=', $lista[$i]['id'])->delete();
                }
            }

            $producto = Producto::findOrFail($request->producto_id);
            $producto->costo_produccion = $request->costo_produccion;
            $producto->save();

            DB::commit();
            $error = NULL;
        } catch (Exception $e) {
            DB::rollback();
            $error = $e;
        }

        return [
            'estado' => $error==NULL?1:0,
            'error' => $error
        ];
    }

    public function listaProductoMaterial(Request $request){
        $lista = ProductoMaterial::select('id', 'material_id', 'nombre', 'unidad', 'costo_unitario', 'cantidad', 'subtotal', DB::raw('1 as estado'))
                                ->where('producto_id', '=', $request->producto_id)
                                ->orderBy('id', 'desc')->get();
        return $lista;
    }
}
