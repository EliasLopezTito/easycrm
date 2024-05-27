<?php

namespace easyCRM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Horario extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'carrera_id', 'turno_id', 'horario', 'tipo'
    ];

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    function carreras()
    {
        return $this->belongsTo('\easyCRM\Carrera', 'carrera_id');
    }

    function turnos()
    {
        return $this->belongsTo('\easyCRM\Turno', 'turno_id');
    }

}
