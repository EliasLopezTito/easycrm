<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModalidadIncidencia extends Model
{
    use SoftDeletes;

    protected $fillable = [
       'image_id', 'clasificacionIncidencia_id', 'name'
    ];

    protected $dates = ['deleted_at'];

    function images() {
        return $this->belongsTo('\Incidencias\Image', 'image_id');
    }

    function clasificacionIncidencias() {
        return $this->belongsTo('\Incidencias\ClasificacionIncidencia', 'clasificacionIncidencia_id');
    }

}
