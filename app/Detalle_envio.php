<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalle_envio extends Model
{
    protected $table = 'detalle_envio';
    protected $fillable = ['envio_id', 'producto_id', 'cantidad'];

    public function getEnvio(){
        return $this->belongsTo('App\Envio');
    }
    public function getProducto(){
        return $this->belongsTo('App\Producto');
    }
}
