<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalle_venta extends Model
{
    protected $table = 'detalle_venta';
    protected $fillable = ['detalle_producto_id', 'venta_id', 'cantidad', 'precio', 'subtotal'];
    public $timestamps = false;

    public function getProducto(){
        return $this->belongsTo('App\Producto');
    }
    public function getVenta(){
        return $this->belongsTo('App\Venta');
    }

}
