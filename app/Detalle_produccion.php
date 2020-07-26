<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalle_produccion extends Model
{
    protected $table = 'detalle_produccion';
    protected $fillable = [
        'nombre_producto', 'costo_produccion', 'cantidad', 'subtotal', 'subproducto_id', 'produccion_id'
    ];

    public $timestamps = false;
}
