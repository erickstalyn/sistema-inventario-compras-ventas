<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoCaracteristica extends Model
{
    protected $table = 'tipo_caracteristica';
    protected $fillable = ['descripcion'];

    public $timestamps = false;
}
