<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoCaracteristica extends Model
{
    protected $table = 'tipo_caracteristica';
    protected $fillable = ['nombre', 'required', 'estado'];

    public $timestamps = false;

    public function getCaracteristicas(){
        return $this->hasMany('App\Caracteristica');
    }
}
