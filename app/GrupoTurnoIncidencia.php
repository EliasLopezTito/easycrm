<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;

class GrupoTurnoIncidencia extends Model
{
    protected $fillable = [
        'grupo_id', 'turno_id', 'incidencia_id', 'estado_id', 'fecha_abierta', 'fecha_cierre'
    ];

    public $timestamps = false;

}
