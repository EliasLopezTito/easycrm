<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Camioneta extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'marca', 'placa', 'numeroCamioneta', 'anio', 'name', 'vinculado'
    ];

    protected $dates = ['deleted_at'];
}
