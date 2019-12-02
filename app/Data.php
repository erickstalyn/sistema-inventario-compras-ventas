<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    protected $table = 'data';
    protected $fillable = ['tipo', 'subtipo', 'nombre'];

    //Relación uno a uno con Material
    public function getMaterial(){
        return $this->hasOne('App\Material', 'unidad_id');
    }

}
