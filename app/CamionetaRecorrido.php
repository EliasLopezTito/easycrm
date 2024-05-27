<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CamionetaRecorrido extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'recorrido_id', 'camioneta_id', 'placa', 'vinculado', 'recorrido'
    ];

    protected $dates = ['deleted_at'];

    function camionetas() {
        return $this->belongsTo('\Incidencias\Camioneta', 'camioneta_id');
    }

}
