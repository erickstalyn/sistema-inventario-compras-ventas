<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalle_producto extends Model
{
    protected $table = 'detalle_producto';
    protected $fillable = ['producto_id', 'usuario_id', 'stock'];
    public $timestamps = false;

    public function getUsuario(){
        return $this->belongsTo('App\Usuario');
    }
    public function getProducto(){
        return $this->belongsTo('App\Producto');
    }
}
