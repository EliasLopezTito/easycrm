<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizacionPersonal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organizacion_id', 'trabajador_id', 'motivo_personal_id','servicio_id', 'regimen'
    ];

    protected $dates = ['deleted_at'];

    function organizacions() {
        return $this->belongsTo('\Incidencias\Incidencia', 'organizacion_id');
    }

    function trabajadores() {
        return $this->belongsTo('\Incidencias\Trabajador', 'trabajador_id');
    }

    function motivosPersonal() {
        return $this->belongsTo('\Incidencias\MotivoPersonal', 'motivo_personal_id');
    }

    function servicios() {
        return $this->belongsTo('\Incidencias\Servicio', 'servicio_id');
    }
}
