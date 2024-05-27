<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;

class IncidenciaHistorial extends Model
{
    protected $fillable = [
        'incidencia_id', 'perfil_id', 'estado_id', 'user_name', 'fecha_cierre'
    ];

    public $timestamps = false;

    function perfils() {
        return $this->belongsTo('\Incidencias\Perfil', 'perfil_id');
    }

    function estados() {
        return $this->belongsTo('\Incidencias\Estado', 'estado_id');
    }

    /*function incidencias() {
        return $this->belongsToMany('\Incidencias\Incidencia', 'incidencia_id');
    }*/
}
