<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    protected $table = 'unidad';
    protected $fillable = ['nombre', 'estado', 'tipo_unidad_id'];

    public $timestamps = false;
}
