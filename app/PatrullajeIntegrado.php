<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatrullajeIntegrado extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'turno_id', 'user_id', 'dia', 'hora_inicio', 'hora_final', 'camioneta_id', 'placa',
        'trabajador_id', 'efectivo_policial', 'fecha_registro', 'estado_id'
    ];

    protected $dates = ['deleted_at'];

    function turnos() {
        return $this->belongsTo('\Incidencias\Turno', 'turno_id');
    }

    function users() {
        return $this->belongsTo('\Incidencias\User', 'user_id');
    }

    function camionetas() {
        return $this->belongsTo('\Incidencias\Camioneta', 'camioneta_id');
    }

    function trabajadors() {
        return $this->belongsTo('\Incidencias\Trabajador', 'trabajador_id');
    }

    function estados() {
        return $this->belongsTo('\Incidencias\Estado', 'estado_id');
    }

    function zonas(){
        return $this->hasMany('\Incidencias\PatrullajeIntegradoSector', 'patrullajeIntegrado_id');
    }
}
