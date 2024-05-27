<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ControlGarPersonal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'control_gar_id', 'trabajador_id', 'motivo_personal_id','radio', 'regimen'
    ];

    protected $dates = ['deleted_at'];

    function controlGars() {
        return $this->belongsTo('\Incidencias\ControlGar', 'control_gar_id');
    }

    function trabajadores() {
        return $this->belongsTo('\Incidencias\Trabajador', 'trabajador_id');
    }

    function motivosPersonal() {
        return $this->belongsTo('\Incidencias\MotivoPersonal', 'motivo_personal_id');
    }

}
