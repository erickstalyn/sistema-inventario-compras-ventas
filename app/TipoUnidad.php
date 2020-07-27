<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoUnidad extends Model
{
    protected $table = 'tipo_unidad';
    protected $fillable = ['nombre'];

    public $timestamps = false;
}
