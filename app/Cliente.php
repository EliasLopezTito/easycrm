<?php

namespace easyCRM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'nombres', 'apellidos', 'dni', 'celular', 'whatsapp', 'email', 'fecha_nacimiento', 'proviene_id', 'provincia', 'provincia_id', 'distrito_id', 'direccion',
        'modalidad_id','carrera_id', 'fuente_id', 'enterado_id', 'ciclo_id', 'estado_id', 'estado_detalle_id', 'reasignado', 'codigo_reingreso', 'semestre_termino_id',
        'ciclo_termino_id', 'semestre_inicio_id', 'ciclo_inicio_id', 'mes', 'cursos_jalados', 'turno_id', 'horario_id', 'sede_id', 'local_id', 'presencial_sede_id', 'presencial_turno_id',
        'presencial_horario_id', 'tipo_operacion_id', 'nro_operacion', 'monto', 'nombre_titular', 'codigo_alumno', 'promocion', 'observacion', 'ultimo_contacto', 'created_modified_by', 'origen_lead'
    ];

    protected $dates = ['deleted_at'];

    function users()
    {
        return $this->belongsTo('\easyCRM\User', 'user_id')->withTrashed();;
    }

    function provienes()
    {
        return $this->belongsTo('\easyCRM\Proviene', 'proviene_id')->withTrashed();;
    }

    function provincias()
    {
        return $this->belongsTo('\easyCRM\Provincia', 'provincia_id')->withTrashed();
    }

    function distritos()
    {
        return $this->belongsTo('\easyCRM\Distrito', 'distrito_id')->withTrashed();
    }

    function modalidades()
    {
        return $this->belongsTo('\easyCRM\Modalidad', 'modalidad_id')->withTrashed();
    }

    function carreras()
    {
        return $this->belongsTo('\easyCRM\Carrera', 'carrera_id')->withTrashed();
    }

    function fuentes()
    {
        return $this->belongsTo('\easyCRM\Fuente', 'fuente_id')->withTrashed();
    }

    function enterados()
    {
        return $this->belongsTo('\easyCRM\Enterado', 'enterado_id')->withTrashed();
    }

    function estados()
    {
        return $this->belongsTo('\easyCRM\Estado', 'estado_id')->withTrashed();
    }

    function estadoDetalle()
    {
        return $this->belongsTo('\easyCRM\EstadoDetalle', 'estado_detalle_id')->withTrashed();
    }

    function turnos()
    {
        return $this->belongsTo('\easyCRM\Turno', 'turno_id')->withTrashed();
    }

    function horarios()
    {
        return $this->belongsTo('\easyCRM\Horario', 'horario_id')->withTrashed();
    }

    function turnosSemiPresencial()
    {
        return $this->belongsTo('\easyCRM\Turno', 'presencial_turno_id')->withTrashed();
    }

    function horariosSemiPresencial()
    {
        return $this->belongsTo('\easyCRM\Horario', 'presencial_horario_id')->withTrashed();
    }

    function sedes()
    {
        return $this->belongsTo('\easyCRM\Sede', 'sede_id')->withTrashed();
    }

    function locales()
    {
        return $this->belongsTo('\easyCRM\Local', 'local_id')->withTrashed();
    }

    function tipoOperaciones()
    {
        return $this->belongsTo('\easyCRM\TipoOperacion', 'tipo_operacion_id')->withTrashed();
    }

    function ciclos()
    {
        return $this->belongsTo('\easyCRM\Ciclo', 'ciclo_id')->withTrashed();
    }

    function semestreInicio()
    {
        return $this->belongsTo('\easyCRM\Semestre', 'semestre_inicio_id')->withTrashed();
    }

    function cicloInicio()
    {
        return $this->belongsTo('\easyCRM\Ciclo', 'ciclo_inicio_id')->withTrashed();
    }

    function matriculados()
    {
        return $this->hasMany('\easyCRM\ClienteMatricula', 'cliente_id')->withTrashed();
    }

    


}
