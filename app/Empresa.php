<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresa';
    protected $fillable = ['ruc', 'razon_social', 'descripcion'];

    public $timestamps = false;
}
