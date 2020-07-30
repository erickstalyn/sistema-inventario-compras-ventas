<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model{

    protected $table = 'producto';
    protected $fillable = ['nombre', 'modelo', 'descripcion', 'stock', 'estado', 'marca_id', 'categoria_id'];
    
    public $timestamps = false;
    
    public function getSubproductos() {
        return $this->hasMany('App\Subproducto');
    }

}
