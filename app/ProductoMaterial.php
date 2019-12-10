<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductoMaterial extends Model
{
    protected $table = 'producto_material';
    protected $fillable = [
        'producto_id', 'material_id', 'nombre', 'unidad', 'costo_unitario', 'cantidad', 'subtotal'
    ];

    public $timestamps = false;
}
