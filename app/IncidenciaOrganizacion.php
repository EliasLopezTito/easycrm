<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;

class IncidenciaOrganizacion extends Model
{
    protected $fillable = [
        'incidencia_id', 'organizacion_id', 'estado_id'
    ];

    public $timestamps = false;

    function incidencias() {
        return $this->belongsTo('\Incidencias\Incidencia', 'incidencia_id');
    }

    function organizaciones() {
        return $this->belongsTo('\Incidencias\Organizacion', 'organizacion_id');
    }

    function estados() {
        return $this->belongsTo('\Incidencias\Estado', 'estado_id');
    }

}
