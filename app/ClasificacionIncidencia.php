<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClasificacionIncidencia extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'categoria_id', 'name'
    ];

    protected $dates = ['deleted_at'];

    function categorias() {
        return $this->belongsTo('\Incidencias\Categoria', 'categoria_id');
    }

}
