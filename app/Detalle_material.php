<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalle_material extends Model
{
    protected $table = 'detalle_material';
    protected $fillable = ['subproducto_id', 'material_id', 'nombre', 'unidad', 'costo_unitario', 'cantidad', 'subtotal'];

    public $timestamps = false;
}
