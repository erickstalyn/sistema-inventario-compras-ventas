<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoCaracteristica extends Model
{
    protected $table = 'tipo_caracteristica';
    protected $fillable = ['nombre'];

    public $timestamps = false;
}
