<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizacionPersonalServicio extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organizacion_id', 'trabajador_id', 'servicio_id','sector_id', 'regimen'
    ];

    protected $dates = ['deleted_at'];

    function organizacions() {
        return $this->belongsTo('\Incidencias\Incidencia', 'organizacion_id');
    }

    function trabajadores() {
        return $this->belongsTo('\Incidencias\Trabajador', 'trabajador_id');
    }

    function servicios() {
        return $this->belongsTo('\Incidencias\Servicio', 'servicio_id');
    }

    function sectores() {
        return $this->belongsTo('\Incidencias\Sector', 'sector_id');
    }

}
