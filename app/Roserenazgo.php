<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;

class Roserenazgo extends Model
{
    protected $fillable = [
        'year',  'clasificacionIncidencia_id', 'modalidadIncidencia_id', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre',
        'octubre', 'noviembre', 'diciembre'
    ];

    function clasificacionIncidencias(){
        return $this->belongsTo('\Incidencias\ClasificacionIncidencia', 'clasificacionIncidencia_id');
    }

    function modalidadIncidencias(){
        return $this->belongsTo('\Incidencias\ModalidadIncidencia', 'modalidadIncidencia_id');
    }
}
