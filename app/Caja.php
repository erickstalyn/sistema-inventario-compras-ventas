<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model {
    
    protected $table = 'caja';
    protected $fillable = [
        'centro_id', 'total_start', 'total_end', 'total_ingreso', 'total_egreso', 'state', 'start', 'end'
    ];

    public $timestamps = false;

    public function getConceptos(){
        return $this->hasMany('App\Concepto');
    }
}
