<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;

class Comisaria extends Model
{
    protected $fillable = [
        'year',  'delitoPNP_id', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre',
        'octubre', 'noviembre', 'diciembre'
    ];

    function delitosPNP(){
        return $this->belongsTo('\Incidencias\DelitoPNP', 'delitoPNP_id');
    }
}
