<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovimientoProducto extends Model
{
    protected $table = 'movimiento_producto';
    protected $fillable = ['detalle_producto_id', 'cantidad', 'estado_producto', 'tipo', 'descripcion'];
}
