<?php

namespace easyCRM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClienteMatricula extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'cliente_id', 'modalidad_adicional_id', 'carrera_adicional_id', 'sede_adicional_id', 'local_adicional_id', 'turno_adicional_id', 'horario_adicional_id',
        'presencial_adicional_sede_id', 'presencial_adicional_turno_id', 'presencial_adicional_horario_id', 'tipo_operacion_adicional_id',
        'nro_operacion_adicional', 'monto_adicional', 'nombre_titular_adicional', 'codigo_alumno_adicional', 'promocion_adicional', 'observacion_adicional',
    ];

    protected $dates = ['deleted_at'];

    function clientes()
    {
        return $this->belongsTo('\easyCRM\Cliente', 'cliente_id')->withTrashed();
    }

    function modalidades()
    {
        return $this->belongsTo('\easyCRM\Modalidad', 'modalidad_adicional_id')->withTrashed();
    }

    function carreras()
    {
        return $this->belongsTo('\easyCRM\Carrera', 'carrera_adicional_id')->withTrashed();
    }

    function turnos()
    {
        return $this->belongsTo('\easyCRM\Turno', 'turno_adicional_id')->withTrashed();
    }

    function horarios()
    {
        return $this->belongsTo('\easyCRM\Horario', 'horario_adicional_id')->withTrashed();
    }

    function sedes()
    {
        return $this->belongsTo('\easyCRM\Sede', 'sede_adicional_id')->withTrashed();
    }

    function turnosSemi()
    {
        return $this->belongsTo('\easyCRM\Turno', 'presencial_adicional_turno_id')->withTrashed();
    }

    function horariosSemi()
    {
        return $this->belongsTo('\easyCRM\Horario', 'presencial_adicional_horario_id')->withTrashed();
    }

    function sedesSemi()
    {
        return $this->belongsTo('\easyCRM\Sede', 'presencial_adicional_sede_id')->withTrashed();
    }

    function tipoOperaciones()
    {
        return $this->belongsTo('\easyCRM\TipoOperacion', 'tipo_operacion_adicional_id')->withTrashed();
    }

    function locales()
    {
        return $this->belongsTo('\easyCRM\Local', 'local_adicional_id')->withTrashed();
    }

}
