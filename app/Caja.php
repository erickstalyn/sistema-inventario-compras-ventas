<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model {
    
    protected $table = 'caja';
    protected $fillable = [
        'centro_id', 'total_start', 'total_end', 'state', 'start', 'end'
    ];

    public $timestamps = false;

    public function getConceptos(){
        $this->hasMany('App\Concepto');
    }
}
