<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produccion extends Model
{
    protected $table = 'produccion';
    protected $fillable = ['total', 'fecha_inicio', 'fecha_programada', 'fecha_fin', 'almacen_id'];
    public $timestamps = false;

}
