<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{

    protected $table = 'persona';
    protected $fillable = [
        'persona_id', 'nombres', 'apellidos', 'razon_social', 'tipo_documento', 'numero_documento', 'direcciones', 'telefonos', 'emails', 'birthday', 'tipo', 'estado'
    ];
    protected $casts = [
        'direcciones' => 'array',
        'telefonos' => 'array',
        'emails' => 'array'
    ];

    public $timestamps = false;
    
    public function getUsuario(){
        return $this->hasOne('App\Usuario', 'persona_id');
    }

}
