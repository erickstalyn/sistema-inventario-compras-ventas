<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversion extends Model
{
    protected $table = 'conversion';
    protected $fillable = ['factor', 'unidad_from_id', 'unidad_to_id'];

    public $timestamps = false;
}
