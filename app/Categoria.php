<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model{

    // use SoftDeletes;

    protected $table = 'categoria';
    protected $fillable = ['nombre', 'descripcion', 'estado'];

    public function getProductos() {
        return $this->hasMany('App\Producto');
    }
    public function getSupraCategoria() {
        return $this->belongsTo('App\Categoria');
    }
    public function getSubCategorias(){
        return $this->hasMany('App\Categoria');
    }
}
