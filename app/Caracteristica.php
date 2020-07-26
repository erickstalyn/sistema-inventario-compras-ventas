<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caracteristica extends Model
{
    protected $table = 'caracteristica';
    protected $fillable = ['descripcion', 'tipo_caracteristica_id'];

    public $timestamps = false;
}
