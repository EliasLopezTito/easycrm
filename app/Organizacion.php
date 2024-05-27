<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organizacion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'turno_id', 'estado_id', 'user_id', 'user_name', 'efectivo', 'descuento', 'total'
    ];

    protected $dates = ['deleted_at'];

    function turnos() {
        return $this->belongsTo('\Incidencias\Turno', 'turno_id');
    }

    function estados() {
        return $this->belongsTo('\Incidencias\Estado', 'estado_id');
    }

    function servicios() {
        return $this->hasMany('\Incidencias\OrganizacionServicio', 'organizacion_id');
    }

    function sectores() {
        return $this->hasMany('\Incidencias\OrganizacionSector', 'organizacion_id');
    }

    function personalesServicio() {
        return $this->hasMany('\Incidencias\OrganizacionPersonalServicio', 'organizacion_id');
    }

    function personalesMotivo() {
        return $this->hasMany('\Incidencias\OrganizacionPersonal', 'organizacion_id');
    }
}
