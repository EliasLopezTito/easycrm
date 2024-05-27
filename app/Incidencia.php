<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incidencia extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'grupo_id', 'turno_id', 'estado_id', 'user_id', 'user_name', 'efectivo', 'descuento', 'total'
    ];

    protected $dates = ['deleted_at'];


    function grupos() {
        return $this->belongsTo('\Incidencias\Grupo', 'grupo_id');
    }

    function turnos() {
        return $this->belongsTo('\Incidencias\Turno', 'turno_id');
    }

    function estados() {
        return $this->belongsTo('\Incidencias\Estado', 'estado_id');
    }

    function historiales() {
        return $this->hasMany('\Incidencias\IncidenciaHistorial', 'incidencia_id');
    }

    function personales() {
        return $this->hasMany('\Incidencias\IncidenciaPersonal', 'incidencia_id');
    }

    function sectores() {
        return $this->hasMany('\Incidencias\IncidenciaSector', 'incidencia_id');
    }

    function parqueAutomotors() {
        return $this->hasMany('\Incidencias\IncidenciaParqueAutomotor', 'incidencia_id');
    }

    function personalFijos() {
        return $this->hasMany('\Incidencias\IncidenciaPersonalFijo', 'incidencia_id');
    }
}
