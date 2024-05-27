<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;

class OrganizacionServicio extends Model
{
    protected $fillable = [
        'organizacion_id', 'servicio_id', 'cantidad'
    ];

    public $timestamps = false;

    function organizacions() {
        return $this->belongsTo('\Incidencias\Organizacion', 'organizacion_id');
    }

    function servicios() {
        return $this->belongsTo('\Incidencias\Servicio', 'servicio_id');
    }
}
