<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncidenciaPersonal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'incidencia_id', 'user_id', 'trabajador_id', 'motivo_personal_id','sector_id', 'regimen'
    ];

    protected $dates = ['deleted_at'];

    function incidencias() {
        return $this->belongsTo('\Incidencias\Incidencia', 'incidencia_id');
    }

    function users() {
        return $this->belongsTo('\Incidencias\User', 'user_id');
    }

    function trabajadores() {
        return $this->belongsTo('\Incidencias\Trabajador', 'trabajador_id');
    }

    function motivosPersonal() {
        return $this->belongsTo('\Incidencias\MotivoPersonal', 'motivo_personal_id');
    }

    function sectores() {
        return $this->belongsTo('\Incidencias\Sector', 'sector_id');
    }
}
