<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trabajador extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'image_id', 'personalCargo_id', 'macro_id', 'grupo_id', 'contrato_id', 'turno_id', 'turno_suplente_id',  'name', 'dni', 'direccion', 'referencia', 'telefono',
        'persona1', 'telefono1', 'persona2', 'telefono2'
    ];

    protected $dates = ['deleted_at'];

    function images() {
        return $this->belongsTo('\Incidencias\Image', 'image_id');
    }

    function personalCargos() {
        return $this->belongsTo('\Incidencias\PersonalCargo', 'personalCargo_id');
    }

    function macros() {
        return $this->belongsTo('\Incidencias\Macro', 'macro_id');
    }

    function grupos() {
        return $this->belongsTo('\Incidencias\Grupo', 'grupo_id');
    }

    function contratos() {
        return $this->belongsTo('\Incidencias\Contrato', 'contrato_id');
    }

    function turnos() {
        return $this->belongsTo('\Incidencias\Turno', 'turno_id');
    }

    function turnosSuplente() {
        return $this->belongsTo('\Incidencias\Turno', 'turno_suplente_id');
    }

}
