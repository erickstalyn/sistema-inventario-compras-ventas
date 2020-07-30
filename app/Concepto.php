<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Concepto extends Model {
    
    protected $table = 'concepto';
    protected $fillable = [
        'caja_id', 'type', 'descripcion', 'monto'
    ];
    
    public $timestamps = false;
}
