<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ControlGar extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'incidencia_id', 'grupo_id', 'turno_id', 'estado_id', 'user_id', 'user_name'
    ];

    protected $dates = ['deleted_at'];

    function incidencias() {
        return $this->belongsTo('\Incidencias\Incidencia', 'incidencia_id');
    }

    function grupos() {
        return $this->belongsTo('\Incidencias\Grupo', 'grupo_id');
    }

    function turnos() {
        return $this->belongsTo('\Incidencias\Turno', 'turno_id');
    }

    function estados() {
        return $this->belongsTo('\Incidencias\Estado', 'estado_id');
    }

    function personales() {
        return $this->hasMany('\Incidencias\ControlGarPersonal', 'control_gar_id');
    }
}
