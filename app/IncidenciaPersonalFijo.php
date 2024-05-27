<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;

class IncidenciaPersonalFijo extends Model
{
    protected $fillable = [
        'incidencia_id', 'personalFijo_id', 'total'
    ];

    public $timestamps = false;

    function personalFijo() {
        return $this->belongsTo('\Incidencias\PersonalFijo', 'personalFijo_id');
    }


}
