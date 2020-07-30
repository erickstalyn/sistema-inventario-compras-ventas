<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pago';
    protected $fillable = ['monto', 'venta_id', 'abasto_id'];

    public $timestamps = false;

    public function getVentaCredito(){
        return $this->belongsTo('App\VentaCredito');
    }
}
