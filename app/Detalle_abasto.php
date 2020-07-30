<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalle_abasto extends Model
{
    protected $table = 'detalle_abasto';
    protected $fillable = [
        'nombre_producto','cantidad', 'costo_abasto', 'subtotal','subproducto_id','abasto_id'
    ];

    public $timestamps = false;

    public function getAbasto(){
        return $this->belongsTo('App\Abasto');
    }

    public function getProducto(){
        return $this->belongsTo('App\Producto');
    }

    
}
