<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'venta';
    protected $fillable = [
        'cliente_id', 'centro_id', 'codigo', 'tipo', 'total', 'total_faltante', 'total_descuento', 'importe_impuesto', 'total_venta', 'direccion', 'usuario_id'
    ];

    public $timestamps = false;
    
    public function getDetalleVenta(){
        return $this->belongsToMany('App\Detalle_producto', 'App\Detalle_venta')->select('substock', 'precio_menor', 'precio_mayor')
                    ->withPivot('id', 'nombre_producto', 'cantidad', 'subtotal', 'cantidad_fallido', 'precio')->as('detalle');
    }
    
    public function getPago(){
        return $this->hasMany('App\Pago');
    }
}
