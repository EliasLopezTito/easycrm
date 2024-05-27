<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recorrido extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'fecha_abierto', 'fecha_cerrado', 'user_id'
    ];

    protected $dates = ['deleted_at'];

    function camionetasRecorrido() {
        return $this->hasMany('\Incidencias\CamionetaRecorrido', 'recorrido_id');
    }

}
