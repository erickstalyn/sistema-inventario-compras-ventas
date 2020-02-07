<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'venta';
    protected $fillable = ['cliente_id', 'centro_id', 'codigo', 'total', 'total_faltante', 'total_descuento', 'total_venta', 'tipo'];
    
    public function getDetalleVenta(){
        return $this->belongsToMany('App\Detalle_producto', 'App\Detalle_venta')->select('substock', 'precio_menor', 'precio_mayor')
                    ->withPivot('id', 'nombre_producto', 'cantidad', 'subtotal', 'cantidad_fallido')->as('detalle');
    }

    public function getPago(){
        return $this->hasMany('App\Pago');
    }
}
