<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caracteristica extends Model
{
    protected $table = 'caracteristica';
    protected $fillable = ['nombre', 'tipo_caracteristica_id'];

    public $timestamps = false;
}
