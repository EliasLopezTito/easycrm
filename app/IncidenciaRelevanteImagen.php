<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;

class IncidenciaRelevanteImagen extends Model
{
    protected $fillable = [
        'incidenciaRelevante_id', 'image_id',
    ];

    public $timestamps = false;

}
