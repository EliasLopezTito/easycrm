<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;

class TurnoOrganizacion extends Model
{
    protected $fillable = [
        'turno_id', 'organizacion_id', 'perfil_id', 'estado_id', 'fecha_abierta', 'fecha_cierre'
    ];

    public $timestamps = false;

    function turnos() {
        return $this->belongsTo('\Incidencias\Turno', 'turno_id');
    }

}
