<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Funcion extends Model {
    
    protected $table = 'funcion';
    protected $fillable = ['descripcion'];
    
    public $timestamps = false;

}
