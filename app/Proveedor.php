<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $fillable = [
        'nombre','telefono', 'contacto', 'telefono_contacto'
    ];

    public function getAbastos()
    {
        return $this->hasMany('App\Abasto');
    }
    
    public function getPersona(){
        return $this->belongsTo('App\Persona');
    }

}
