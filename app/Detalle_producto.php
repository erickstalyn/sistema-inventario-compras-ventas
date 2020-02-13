<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalle_producto extends Model
{
    protected $table = 'detalle_producto';
    protected $fillable = ['producto_id', 'centro_id', 'substock', 'reservados', 'fallidos', 'traslado', 'precio_menor', 'precio_mayor'];
    public $timestamps = false;

    public function getUsuario(){
        return $this->belongsTo('App\Usuario');
    }
    public function getProducto(){
        return $this->belongsTo('App\Producto');
    }
}
