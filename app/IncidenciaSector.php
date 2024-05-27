<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncidenciaSector extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'incidencia_id', 'cargo_id' ,'sector_id', 'cantidad', 'subtotal'
    ];

    protected $dates = ['deleted_at'];

    function incidencias() {
        return $this->belongsTo('\Incidencias\Incidencia', 'incidencia_id');
    }

    function cargos() {
        return $this->belongsTo('\Incidencias\Cargo', 'cargo_id');
    }

    function sectores() {
        return $this->belongsTo('\Incidencias\Sector', 'sector_id');
    }
}
