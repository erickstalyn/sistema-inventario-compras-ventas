<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vale extends Model {

    protected $table = 'vale';
    protected $fillable = ['persona_id', 'venta_usada_id', 'venta_generada_id', 'monto'];
    
}
