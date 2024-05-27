<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;

class IncidenciaParqueAutomotor extends Model
{
    protected $fillable = [
        'incidencia_id', 'user_id', 'parque_automotor_id', 'operativo','inoperativo'
    ];

    public $timestamps = false;

    function incidencias() {
        return $this->belongsTo('\Incidencias\Incidencia', 'incidencia_id');
    }

    function users() {
        return $this->belongsTo('\Incidencias\User', 'user_id');
    }

    function parqueAutomotors() {
        return $this->belongsTo('\Incidencias\ParqueAutomotor', 'parque_automotor_id');
    }
}
