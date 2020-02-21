<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalle_funcion extends Model
{
    protected $table = 'detalle_funcion';
    protected $fillable = ['estado', 'persona_id', 'funcion_id'];
    public $timestamps = false;
    
}
