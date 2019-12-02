<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuperProducto extends Model{

    protected $table = 'superproducto';
    protected $fillable = ['nombre', 'descripcion', 'superstock', 'estado', 'created_at'];
    public $timestamps = false;
    
    public function getProductos() {
        return $this->hasMany('App\Producto');
    }

}
