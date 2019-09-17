<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalle_abasto extends Model
{
    public $timestamps = false;
    protected $table= 'detalle_abasto';
    protected $fillable = [
        'producto_id','abasto_id', 'cantidad', 'precio', 'subtotal'
    ];

    public function getAbasto(){
        return $this->belongsTo('App\Abasto');
    }

    public function getProducto(){
        return $this->belongsTo('App\Producto');
    }

    
}
